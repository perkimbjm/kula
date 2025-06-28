<?php
// File ini dibuat untuk meng-handle import data ConsolPlanDetail dari file XLSX
// Kolom yang diimport: name, ee, nego_value. Kolom no diabaikan.

namespace App\Filament\Imports;

use App\Models\ConsolPlanDetail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ConsolPlanDetailImporter implements WithHeadingRow, WithStartRow, ToCollection, WithBatchInserts
{
    public function startRow(): int
    {
        return 2; // Mulai dari baris kedua (setelah header)
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            \Log::info('Row imported:', $row->toArray());
            // Mapping baru kolom excel
            $name = $row['nama_paket'] ?? null;
            $ee = $row['hps'] ?? null;
            $negoValue = $row['harga_negosiasi'] ?? null;

            if (is_null($name) || is_null($ee) || is_null($negoValue)) {
                \Log::warning('Data kosong atau header tidak cocok:', $row->toArray());
                continue; // Skip jika ada yang kosong
            }
            if (!is_numeric($ee) || $ee < 0) {
                \Log::warning('HPS tidak valid:', ['ee' => $ee, 'row' => $row->toArray()]);
                continue;
            }
            if (!is_numeric($negoValue) || $negoValue < 0) {
                \Log::warning('Harga Negosiasi tidak valid:', ['nego_value' => $negoValue, 'row' => $row->toArray()]);
                continue;
            }
            if (preg_match('/^\d+(\.\d{1,2})?$/', $ee) !== 1) {
                \Log::warning('HPS tidak sesuai format dua digit:', ['ee' => $ee, 'row' => $row->toArray()]);
                continue;
            }
            if (preg_match('/^\d+(\.\d{1,2})?$/', $negoValue) !== 1) {
                \Log::warning('Harga Negosiasi tidak sesuai format dua digit:', ['nego_value' => $negoValue, 'row' => $row->toArray()]);
                continue;
            }

            ConsolPlanDetail::create([
                'name' => $name,
                'ee' => $ee,
                'nego_value' => $negoValue,
            ]);
            \Log::info('Data berhasil diimport:', ['name' => $name, 'ee' => $ee, 'nego_value' => $negoValue]);
        }
    }

    public function batchSize(): int
    {
        return 100;
    }
}
