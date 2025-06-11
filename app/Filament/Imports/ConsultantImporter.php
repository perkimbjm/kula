<?php

namespace App\Filament\Imports;

use App\Models\Consultant;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class ConsultantImporter implements WithHeadingRow, WithStartRow, ToModel, WithBatchInserts
{
    public function startRow(): int
    {
        return 2; // Mulai membaca dari baris ke-2 (setelah header)
    }

    protected $columnMapping = [
        'Penyedia Jasa' => 'name',
        'No. Akta Terakhir' => 'akta',
        'Tanggal' => 'founding_date',
        'Notaris' => 'notary',
        'Alamat Penyedia' => 'address',
        'NPWP' => 'npwp',
        'Nama Direktur' => 'leader',
        'Jabatan' => 'position',
        'Bank' => 'bank',
        'Nomor Rekening' => 'account_number',
        'Nama Nasabah' => 'account_holder',
    ];

    public function model(array $row)
    {
        try {
            Log::info('Processing consultant row:', $row);

            $mappedRow = [];
            foreach ($this->columnMapping as $excelColumn => $dbColumn) {
                $cleanedColumn = trim($excelColumn);
                $mappedRow[$dbColumn] = $row[$cleanedColumn] ?? null;
            }

            Log::info('Mapped consultant data:', $mappedRow);

            // Skip empty rows
            if (empty(array_filter($mappedRow))) {
                Log::info('Skipping empty row');
                return null;
            }

            return new Consultant([
                'name' => $mappedRow['name'],
                'akta' => $mappedRow['akta'],
                'founding_date' => !empty($mappedRow['founding_date']) ?
                    (is_numeric($mappedRow['founding_date']) ?
                        \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($mappedRow['founding_date'])->format('Y-m-d') :
                        \Carbon\Carbon::parse($mappedRow['founding_date'])->format('Y-m-d')
                    ) : null,
                'notary' => $mappedRow['notary'],
                'address' => $mappedRow['address'],
                'npwp' => $mappedRow['npwp'],
                'leader' => $mappedRow['leader'],
                'position' => $mappedRow['position'],
                'bank' => $mappedRow['bank'],
                'account_number' => $mappedRow['account_number'],
                'account_holder' => $mappedRow['account_holder'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing consultant row:', [
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
