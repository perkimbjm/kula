<?php

namespace App\Filament\Imports;

use App\Models\Work;
use App\Models\Facility;
use App\Enums\ProgressStatus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class FacilityImporter implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        Log::info('FacilityImporter: Starting import process', ['total_rows' => $rows->count()]);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because index starts from 0 and we have header row

            try {
                Log::info("FacilityImporter: Processing row {$rowNumber}", ['row_data' => $row->toArray()]);

                // Map column headers to database fields
                $mappedRow = $this->mapRowData($row);

                Log::info("FacilityImporter: Mapped data", ['mapped_data' => $mappedRow]);

                // Validate required fields
                if (empty($mappedRow['work_id'])) {
                    Log::warning("FacilityImporter: Skipping row {$rowNumber} - missing required work_id");
                    continue;
                }

                // Validate progress status
                if (!empty($mappedRow['progress_status'])) {
                    try {
                        $mappedRow['progress_status'] = ProgressStatus::from($mappedRow['progress_status']);
                    } catch (\Exception $e) {
                        Log::warning("FacilityImporter: Invalid progress status '{$mappedRow['progress_status']}' for row {$rowNumber}, using default 'berjalan'");
                        $mappedRow['progress_status'] = ProgressStatus::BERJALAN;
                    }
                } else {
                    // Jika kosong, set default ke 'berjalan'
                    $mappedRow['progress_status'] = ProgressStatus::BERJALAN;
                }

                // Validate and clean up the data
                $cleanedRow = [];
                foreach ($mappedRow as $key => $value) {
                    if (!is_null($value) && $value !== '') {
                        $cleanedRow[$key] = $value;
                    }
                }

                // Validate work relationship
                if (!empty($cleanedRow['work_id'])) {
                    $work = DB::table('works')
                        ->where('id', $cleanedRow['work_id'])
                        ->first();

                    if (!$work) {
                        Log::warning("FacilityImporter: Work ID {$cleanedRow['work_id']} not found for row {$rowNumber}");
                        continue;
                    }
                }

                Log::info("FacilityImporter: Final data for row {$rowNumber}", ['final_data' => $cleanedRow]);

                // Create Facility
                $facility = Facility::create($cleanedRow);

                Log::info("FacilityImporter: Successfully processed row {$rowNumber}", ['facility_id' => $facility->id]);

            } catch (\Exception $e) {
                Log::error("FacilityImporter: Error processing row {$rowNumber}: " . $e->getMessage());
                Log::error("FacilityImporter: Stack trace: " . $e->getTraceAsString());

                // Continue with next row instead of stopping entire import
                continue;
            }
        }

        Log::info('FacilityImporter: Import process completed');
    }

    private function mapRowData(Collection $row): array
    {
        // Map Excel columns to database fields
        return [
            'work_id' => $row['work_id'] ?? null,
            'rt' => $row['rt'] ?? null,
            'length' => $this->parseNumeric($row['panjang'] ?? null),
            'width' => $this->parseNumeric($row['lebar'] ?? null),
            'phone' => $row['telepon'] ?? null,
            'construct_type' => $row['konstruksi'] ?? null,
            'lat' => $this->parseCoordinate($row['latitude'] ?? null),
            'lng' => $this->parseCoordinate($row['longitude'] ?? null),
            'progress_status' => $row['progress_status'] ?? null,
            'real_1' => $this->parseNumeric($row['progress_minggu_1'] ?? null),
            'real_2' => $this->parseNumeric($row['progress_minggu_2'] ?? null),
            'real_3' => $this->parseNumeric($row['progress_minggu_3'] ?? null),
            'real_4' => $this->parseNumeric($row['progress_minggu_4'] ?? null),
            'real_5' => $this->parseNumeric($row['progress_minggu_5'] ?? null),
            'real_6' => $this->parseNumeric($row['progress_minggu_6'] ?? null),
            'note' => $row['catatan_konsultan'] ?? null,
            'photo_0_url' => $row['foto_1_url'] ?? null,
            'photo_50_url' => $row['foto_2_url'] ?? null,
            'photo_100_url' => $row['foto_3_url'] ?? null,
            'photo_pho_url' => $row['foto_4_url'] ?? null,
            'shop_drawing_url' => $row['shop_drawing_url'] ?? null,
            'asbuilt_drawing_url' => $row['asbuilt_drawing_url'] ?? null,
            'rab_url' => $row['rab_url'] ?? null,
            'laporan_url' => $row['laporan_url'] ?? null,
            'file_shp_url' => $row['file_shp_url'] ?? null,
            'file_konsultan_perencana_url' => $row['file_konsultan_perencana_url'] ?? null,
            'file_konsultan_pengawas_url' => $row['file_konsultan_pengawas_url'] ?? null,
            'file_kontraktor_pelaksana_url' => $row['file_kontraktor_pelaksana_url'] ?? null,
        ];
    }

    private function parseCoordinate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Convert to string and trim
        $value = trim((string) $value);

        // Coordinates should always use dot as decimal separator (international standard)
        // No thousand separators needed for coordinates
        // Valid range: lat (-90 to 90), lng (-180 to 180)

        // Remove any spaces
        $value = str_replace(' ', '', $value);

        // For coordinates, we expect standard decimal format (dot as decimal)
        // No conversion needed, just validate it's numeric
        return is_numeric($value) ? (float) $value : null;
    }

    private function parseNumeric($value)
    {
        if (empty($value)) {
            return null;
        }

        // Convert to string and trim
        $value = trim((string) $value);

        // Handle Indonesian number format for progress/financial fields
        // Format: 1.000.000,50 (titik sebagai pemisah ribuan, koma sebagai desimal)
        // atau: 10,5 (koma sebagai desimal tanpa pemisah ribuan)

        // Remove currency symbols
        $value = str_replace(['Rp.', 'Rp', ' '], '', $value);

        // Check if it's Indonesian format with comma as decimal separator
        if (strpos($value, ',') !== false) {
            // Split by comma to separate integer and decimal parts
            $parts = explode(',', $value);

            if (count($parts) == 2) {
                // Remove dots from integer part (thousand separators)
                $integerPart = str_replace('.', '', $parts[0]);
                $decimalPart = $parts[1];

                // Reconstruct with dot as decimal separator
                $value = $integerPart . '.' . $decimalPart;
            }
        } else {
            // If no comma, check if dots are thousand separators or decimal point
            $dotCount = substr_count($value, '.');

            if ($dotCount >= 1) {
                $dotPos = strrpos($value, '.');
                $afterDot = substr($value, $dotPos + 1);

                // If multiple dots OR exactly 3 digits after last dot, treat as thousand separators
                if ($dotCount > 1 || strlen($afterDot) == 3) {
                    $value = str_replace('.', '', $value);
                }
                // If 1-2 digits after single dot, treat as decimal point (keep it)
                // If more than 3 digits after single dot, treat as thousand separator
                elseif ($dotCount == 1 && strlen($afterDot) > 3) {
                    $value = str_replace('.', '', $value);
                }
            }
        }

        return is_numeric($value) ? (float) $value : null;
    }
}
