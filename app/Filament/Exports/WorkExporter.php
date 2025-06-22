<?php

namespace App\Filament\Exports;

use App\Models\Work;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class WorkExporter implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $filters;
    protected $selectedColumns;
    protected static $number = 1;

    public function __construct(array $filters = [], array $selectedColumns = [])
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
    }

    public function query()
    {
        $query = Work::forExport();

        // Apply filters if provided
        if (isset($this->filters['contractor']['value'])) {
            $query->where('contractor_id', $this->filters['contractor']['value']);
        }

        if (isset($this->filters['district']['value'])) {
            $query->where('district_id', $this->filters['district']['value']);
        }

        if (isset($this->filters['year']['value'])) {
            $query->where('year', $this->filters['year']['value']);
        }

        if (isset($this->filters['account_code']['value'])) {
            $query->where('account_code', $this->filters['account_code']['value']);
        }

        if (isset($this->filters['program']['value'])) {
            $query->where('program', $this->filters['program']['value']);
        }

        return $query;
    }

    public static function getColumns(): array
    {
        return [
            'no' => 'No',
            'contract_number' => 'No. Kontrak',
            'name' => 'Nama Paket',
            'district.name' => 'Kecamatan',
            'village.name' => 'Desa',
            'rt' => 'RT',
            'length' => 'Panjang',
            'width' => 'Lebar',
            'construction_type' => 'Konstruksi',
            'phone' => 'Telepon',
            'coordinate_lat' => 'Koordinat Latitude',
            'coordinate_lng' => 'Koordinat Longitude',
            'account_code' => 'Kode Rekening',
            'program' => 'Program',
            'source' => 'Sumber',
            'year' => 'Tahun',
            'nego_value' => 'Harga Nego',
            'contractor.name' => 'Kontraktor',
            'supervisor.name' => 'Konsultan Pengawas',
            'consultant.name' => 'Konsultan Perencana',
            'officers.name' => 'Tim Teknis',
            'procurementOfficer.name' => 'Pejabat Pengadaan',
            'duration' => 'Masa (hari)',
            'hps' => 'HPS',
            'bid_value' => 'Nilai Penawaran',
            'correction_value' => 'Koreksi Aritmatik',
            'invite_date' => 'Undangan',
            'evaluation_date' => 'Evaluasi',
            'nego_date' => 'Negosiasi',
            'bahpl_date' => 'BA-HPL',
            'sppbj_date' => 'SPPBJ',
            'spk_date' => 'SPK',
            'add_number' => 'Nomor Addendum',
            'addendum_value' => 'Nilai Addendum',
            'addendum_date' => 'Tanggal Addendum',
            'completion_letter' => 'Surat Keterangan Selesai',
            'completion_date' => 'Tanggal Selesai',
            'pho_date' => 'Tanggal PHO',
            'advance_bap_number' => 'No BAP Uang Muka',
            'advance_guarantee_number' => 'No. Jaminan Uang Muka',
            'advance_guarantor' => 'Penjamin Uang Muka',
            'advance_guarantee_date' => 'Tanggal Jaminan Uang Muka',
            'advance_value' => 'Nilai Uang Muka',
            'advance_payment_date' => 'Tanggal Pembayaran Uang Muka',
            'final_bap_number' => 'No BAP Pelunasan',
            'maintenance_guarantee_number' => 'No. Jaminan Pemeliharaan',
            'final_guarantor' => 'Penjamin Pelunasan',
            'final_guarantee_date' => 'Tanggal Jaminan Pelunasan',
            'final_guarantee_value' => 'Nilai Jaminan Pelunasan',
            'final_payment_date' => 'Tanggal Pembayaran Pelunasan',

        ];
    }

    public function headings(): array
    {
        $columns = static::getColumns();

        if (!empty($this->selectedColumns)) {
            $columns = array_filter($columns, function ($key) {
                return in_array($key, $this->selectedColumns);
            }, ARRAY_FILTER_USE_KEY);
        }

        return array_values($columns);
    }

    public function map($work): array
    {
        $columns = static::getColumns();

        if (!empty($this->selectedColumns)) {
            $columns = array_filter($columns, function ($key) {
                return in_array($key, $this->selectedColumns);
            }, ARRAY_FILTER_USE_KEY);
        }

        $row = [];

        foreach (array_keys($columns) as $field) {
            if ($field === 'no') {
                $row[] = self::$number++;
                continue;
            }

            // Handle date fields formatting
            $dateFields = [
                'invite_date', 'evaluation_date', 'nego_date', 'bahpl_date',
                'sppbj_date', 'spk_date', 'addendum_date', 'completion_date',
                'pho_date', 'advance_guarantee_date', 'advance_payment_date',
                'final_guarantee_date', 'final_payment_date'
            ];

            if (in_array($field, $dateFields)) {
                $row[] = $work->$field ? $work->$field->format('d-m-Y') : '';
                continue;
            }

            // Handle relationship fields
            if (str_contains($field, '.')) {
                if ($field === 'officers.name') {
                    $row[] = $work->officers ? $work->officers->pluck('name')->implode(', ') : '';
                    continue;
                }

                if ($field === 'district.name') {
                    $row[] = $work->district ? $work->district->name : '';
                    continue;
                }

                if ($field === 'village.name') {
                    $row[] = $work->village ? $work->village->name : '';
                    continue;
                }

                if ($field === 'contractor.name') {
                    $row[] = $work->contractor ? $work->contractor->name : '';
                    continue;
                }

                if ($field === 'consultant.name') {
                    $row[] = $work->consultant ? $work->consultant->name : '';
                    continue;
                }

                if ($field === 'supervisor.name') {
                    $row[] = $work->supervisor ? $work->supervisor->name : '';
                    continue;
                }

                if ($field === 'procurementOfficer.name') {
                    $row[] = $work->procurementOfficer ? $work->procurementOfficer->name : '';
                    continue;
                }
            }


            // Default field access
            $row[] = $work->$field ?? '';
        }

        return $row;
    }
}
