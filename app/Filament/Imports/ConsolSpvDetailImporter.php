<?php
// File ini dibuat untuk meng-handle import data ConsolSpvDetail dari file XLSX
// Kolom yang diimport: nama_paket, hps, harga_negosiasi. Kolom no diabaikan.

namespace App\Filament\Imports;

use App\Models\ConsolSpvDetail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ConsolSpvDetailImporter implements WithHeadingRow, WithStartRow, ToCollection, WithBatchInserts
{
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (setelah header)
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $name = $row['nama_paket'] ?? null;
            $ee = $row['hps'] ?? null;
            $negoValue = $row['harga_negosiasi'] ?? null;

            if (is_null($name) || is_null($ee) || is_null($negoValue)) {
                continue; // Skip jika ada yang kosong
            }
            if (!is_numeric($ee) || $ee < 0) {
                continue;
            }
            if (!is_numeric($negoValue) || $negoValue < 0) {
                continue;
            }
            if (preg_match('/^\d+(\.\d{1,2})?$/', $ee) !== 1) {
                continue;
            }
            if (preg_match('/^\d+(\.\d{1,2})?$/', $negoValue) !== 1) {
                continue;
            }

            ConsolSpvDetail::create([
                'name' => $name,
                'ee' => $ee,
                'nego_value' => $negoValue,
            ]);
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
}
