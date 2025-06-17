<?php

namespace App\Filament\Imports;

use App\Models\Plan;
use App\Models\Consultant;
use App\Models\ProcurementOfficer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class PlanImporter implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        Log::info('PlanImporter: Starting import process', ['total_rows' => $rows->count()]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because index starts from 0 and we have header row

            try {
                Log::info("PlanImporter: Processing row {$rowNumber}", ['row_data' => $row->toArray()]);

                // Map column headers to database fields
                $mappedRow = $this->mapRowData($row);

                Log::info("PlanImporter: Mapped data", ['mapped_data' => $mappedRow]);

                // Validate required fields
                if (empty($mappedRow['contract_number']) || empty($mappedRow['program'])) {
                    Log::warning("PlanImporter: Skipping row {$rowNumber} - missing required fields");
                    continue;
                }

                // Convert dates
                $dateFields = [
                    'invite_date', 'evaluation_date', 'nego_date', 'BAHPL_date',
                    'sppbj_date', 'spk_date', 'payment_date'
                ];

                foreach ($dateFields as $dateField) {
                    if (!empty($mappedRow[$dateField])) {
                        $mappedRow[$dateField] = is_numeric($mappedRow[$dateField]) ?
                            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($mappedRow[$dateField])->format('Y-m-d') :
                            \Carbon\Carbon::parse($mappedRow[$dateField])->format('Y-m-d');
                    }
                }

                // Validate and clean up the data
                $cleanedRow = [];
                foreach ($mappedRow as $key => $value) {
                    if (!is_null($value) && $value !== '') {
                        $cleanedRow[$key] = $value;
                    }
                }

                // Validate relationships
                if (!empty($cleanedRow['procurement_officer_id'])) {
                    $procurementOfficer = DB::table('procurement_officers')
                        ->where('id', $cleanedRow['procurement_officer_id'])
                        ->first();

                    if (!$procurementOfficer) {
                        Log::warning("PlanImporter: ProcurementOfficer ID {$cleanedRow['procurement_officer_id']} not found for row {$rowNumber}");
                        unset($cleanedRow['procurement_officer_id']);
                    }
                }

                if (!empty($cleanedRow['consultant_id'])) {
                    $consultant = DB::table('consultants')
                        ->where('id', $cleanedRow['consultant_id'])
                        ->first();

                    if (!$consultant) {
                        Log::warning("PlanImporter: Consultant ID {$cleanedRow['consultant_id']} not found for row {$rowNumber}");
                        unset($cleanedRow['consultant_id']);
                    }
                }

                Log::info("PlanImporter: Final data for row {$rowNumber}", ['final_data' => $cleanedRow]);

                // Create or update Plan (without ID field, let auto-increment handle it)
                $plan = Plan::updateOrCreate(
                    ['contract_number' => $cleanedRow['contract_number']],
                    $cleanedRow
                );

                Log::info("PlanImporter: Successfully processed row {$rowNumber}", ['plan_id' => $plan->id]);

            } catch (\Exception $e) {
                Log::error("PlanImporter: Error processing row {$rowNumber}: " . $e->getMessage());
                Log::error("PlanImporter: Stack trace: " . $e->getTraceAsString());

                // Continue with next row instead of stopping entire import
                continue;
            }
        }

        Log::info('PlanImporter: Import process completed');
    }

    private function mapRowData(Collection $row): array
    {
        // Map Excel columns to database fields
        // Headers after WithHeadingRow conversion: ["no","nomor_kontrak","paket","pejabat_pengadaan","waktu_pelaksanaan","hps","penawaran","aritmatik","harga_spk","penyediaperusahaan","tanggal_undangan","tanggal_evaluasi","tanggal_negosiasi","tanggal_ba_hpl","tanggal_sppbj","tanggal_spk","sumber_dana","tahun","nomor_addendum","tanggal","nilai","ba_lkpp"]

        return [
            // Skip 'no' column - it's just for Excel numbering (auto-increment ID will be used)
            'contract_number' => $row['nomor_kontrak'] ?? null,
            'program' => $row['paket'] ?? null,
            'procurement_officer_id' => $row['pejabat_pengadaan'] ?? null,
            'duration' => $row['waktu_pelaksanaan'] ?? null,
            'oe' => $this->parseNumeric($row['hps'] ?? null),
            'bid_value' => $this->parseNumeric($row['penawaran'] ?? null),
            'correction_value' => $this->parseNumeric($row['aritmatik'] ?? null),
            'nego_value' => $this->parseNumeric($row['harga_spk'] ?? null),
            'consultant_id' => $row['penyediaperusahaan'] ?? null,
            'invite_date' => $row['tanggal_undangan'] ?? null,
            'evaluation_date' => $row['tanggal_evaluasi'] ?? null,
            'nego_date' => $row['tanggal_negosiasi'] ?? null,
            'BAHPL_date' => $row['tanggal_ba_hpl'] ?? null,
            'sppbj_date' => $row['tanggal_sppbj'] ?? null,
            'spk_date' => $row['tanggal_spk'] ?? null,
            'account_type' => $row['sumber_dana'] ?? null,
            'year' => $row['tahun'] ?? date('Y'),
            'addendum_number' => $row['nomor_addendum'] ?? null,
            'payment_date' => $row['tanggal'] ?? null,
            'payment_value' => $this->parseNumeric($row['nilai'] ?? null),
            'ba_lkpp' => $row['ba_lkpp'] ?? null,
        ];
    }

    private function parseNumeric($value)
    {
        if (empty($value)) {
            return null;
        }

        // Remove currency symbols and formatting
        $value = str_replace(['Rp.', 'Rp', '.', ','], '', $value);
        $value = trim($value);

        return is_numeric($value) ? (float) $value : null;
    }
}
