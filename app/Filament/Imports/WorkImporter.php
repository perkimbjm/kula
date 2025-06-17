<?php

namespace App\Filament\Imports;

use App\Models\Work;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Collection;

HeadingRowFormatter::default('none');

class WorkImporter implements WithHeadingRow, WithStartRow, ToCollection, WithBatchInserts
{
    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris ke-2 (setelah header)
    }

    protected $columnMapping = [
        'No. Kontrak' => 'contract_number',
        'Nama Paket' => 'name',
        'ID Kecamatan' => 'district_id',
        'ID Desa' => 'village_id',
        'RT' => 'rt',
        'Lebar' => 'width',
        'Panjang' => 'length',
        'Konstruksi' => 'construction_type',
        'Telepon' => 'phone',
        'Koordinat Latitude' => 'coordinate_lat',
        'Koordinat Longitude' => 'coordinate_lng',
        'Kode Rekening' => 'account_code',
        'Program' => 'program',
        'Sumber' => 'source',
        'Tahun' => 'year',
        'ID Kontraktor' => 'contractor_id',
        'ID Konsultan Perencana' => 'consultant_id',
        'ID Konsultan Pengawas' => 'supervisor_id',
        'ID Tim Teknis' => 'technical_team',
        'ID Pejabat Pengadaan' => 'procurement_officer_id',
        'Masa (hari)' => 'duration',
        'HPS' => 'hps',
        'Nilai Penawaran' => 'bid_value',
        'Koreksi Aritmatik' => 'correction_value',
        'Harga Nego' => 'nego_value',
        'Tanggal Undangan' => 'invite_date',
        'Tanggal Evaluasi' => 'evaluation_date',
        'Tanggal Negosiasi' => 'nego_date',
        'Tanggal BA-HPL' => 'bahpl_date',
        'Tanggal SPPBJ' => 'sppbj_date',
        'Tanggal SPK' => 'spk_date',
        'Nomor Addendum' => 'add_number',
        'Nilai Addendum' => 'addendum_value',
        'Tanggal Addendum' => 'addendum_date',
        'Surat Keterangan Selesai' => 'completion_letter',
        'Tanggal Selesai' => 'completion_date',
        'Tanggal PHO' => 'pho_date',
        'No BAP Uang Muka' => 'advance_bap_number',
        'No. Jaminan Uang Muka' => 'advance_guarantee_number',
        'Penjamin Uang Muka' => 'advance_guarantor',
        'Tanggal Jaminan Uang Muka' => 'advance_guarantee_date',
        'Nilai Uang Muka' => 'advance_value',
        'Tanggal Pembayaran Uang Muka' => 'advance_payment_date',
        'No BAP Pelunasan' => 'final_bap_number',
        'No. Jaminan Pemeliharaan' => 'maintenance_guarantee_number',
        'Penjamin Pelunasan' => 'final_guarantor',
        'Tanggal Jaminan Pelunasan' => 'final_guarantee_date',
        'Nilai Jaminan Pelunasan' => 'final_guarantee_value',
        'Tanggal Pembayaran Pelunasan' => 'final_payment_date',
    ];

    protected $technicalTeamMapping = [];

    public function collection(Collection $collection)
    {
        DB::transaction(function () use ($collection) {
            foreach ($collection as $row) {
                $workData = $this->processRow($row->toArray());
                if ($workData) {
                    Log::info('Creating work with data:', $workData['work_data']);

                    $work = Work::create($workData['work_data']);

                    Log::info('Work created with ID: ' . $work->id);

                    // Handle Tim Teknis relationship
                    if (!empty($workData['technical_team_ids'])) {
                        Log::info('Syncing officers for work ID ' . $work->id, $workData['technical_team_ids']);

                        $syncResult = $work->officers()->sync($workData['technical_team_ids']);

                        Log::info('Sync result:', $syncResult);

                        // Verifikasi apakah sync berhasil
                        $work->refresh();
                        $attachedOfficers = $work->officers()->pluck('name')->toArray();

                        Log::info('Attached officers after sync for work ID ' . $work->id . ':', $attachedOfficers);
                    } else {
                        Log::info('No technical team IDs to sync for work ID: ' . $work->id);
                    }
                }
            }
        });
    }

    protected function processRow(array $row)
    {
        try {
            Log::info('Processing work row:', $row);

            $mappedRow = [];
            foreach ($this->columnMapping as $excelColumn => $dbColumn) {
                $cleanedColumn = trim($excelColumn);
                $mappedRow[$dbColumn] = $row[$cleanedColumn] ?? null;
            }

            Log::info('Mapped work data:', $mappedRow);

            // Skip empty rows
            if (empty(array_filter($mappedRow))) {
                Log::info('Skipping empty work row');
                return null;
            }

            // Handle Tim Teknis (multiple IDs separated by comma)
            $technicalTeamIds = [];
            if (!empty($mappedRow['technical_team'])) {
                $teamIds = array_map('trim', explode(',', $mappedRow['technical_team']));

                foreach ($teamIds as $teamId) {
                    if (!empty($teamId) && is_numeric($teamId)) {
                        $validId = DB::table('officers')->where('id', $teamId)->value('id');
                        if ($validId) {
                            $technicalTeamIds[] = $validId;
                        }
                    }
                }
            }

            // Remove technical_team dari work data dan simpan untuk relasi
            unset($mappedRow['technical_team']);

            // Handle date fields
            $dateFields = [
                'invite_date', 'evaluation_date', 'nego_date', 'bahpl_date',
                'sppbj_date', 'spk_date', 'addendum_date', 'completion_date',
                'pho_date', 'advance_guarantee_date', 'advance_payment_date',
                'final_guarantee_date', 'final_payment_date'
            ];

            foreach ($dateFields as $dateField) {
                if (!empty($mappedRow[$dateField])) {
                    $mappedRow[$dateField] = is_numeric($mappedRow[$dateField]) ?
                        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($mappedRow[$dateField])->format('Y-m-d') :
                        \Carbon\Carbon::parse($mappedRow[$dateField])->format('Y-m-d');
                }
            }

            // Handle relationship IDs - validate they exist
            if (!empty($mappedRow['district_id'])) {
                $mappedRow['district_id'] = DB::table('districts')->where('id', $mappedRow['district_id'])->value('id');
            }

            if (!empty($mappedRow['village_id'])) {
                $mappedRow['village_id'] = DB::table('villages')->where('id', $mappedRow['village_id'])->value('id');
            }

            if (!empty($mappedRow['contractor_id'])) {
                $mappedRow['contractor_id'] = DB::table('contractors')->where('id', $mappedRow['contractor_id'])->value('id');
            }

            if (!empty($mappedRow['consultant_id'])) {
                $mappedRow['consultant_id'] = DB::table('consultants')->where('id', $mappedRow['consultant_id'])->value('id');
            }

            if (!empty($mappedRow['supervisor_id'])) {
                $mappedRow['supervisor_id'] = DB::table('consultants')->where('id', $mappedRow['supervisor_id'])->value('id');
            }

            if (!empty($mappedRow['procurement_officer_id'])) {
                $mappedRow['procurement_officer_id'] = DB::table('procurement_officers')->where('id', $mappedRow['procurement_officer_id'])->value('id');
            }

            return [
                'work_data' => $mappedRow,
                'technical_team_ids' => $technicalTeamIds
            ];
        } catch (\Exception $e) {
            Log::error('Error processing work row:', [
                'row' => $row,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
}
