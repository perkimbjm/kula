<?php

namespace App\Filament\Exports;

use App\Models\Facility;
use App\Enums\ProgressStatus;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Filament\Actions\Exports\ExportColumn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FacilityExporter implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Facility::query()->with([
            'work',
            'work.district',
            'work.village',
            'work.procurementOfficer',
            'work.officers'
        ]);

        // Apply filters if provided
        if (isset($this->filters['work_id']['value'])) {
            $query->where('work_id', $this->filters['work_id']['value']);
        }

        if (isset($this->filters['progress_status']['value'])) {
            $query->where('progress_status', $this->filters['progress_status']['value']);
        }

        return $query;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no')
                ->label('No'),
            ExportColumn::make('work.contract_number')
                ->label('No. Kontrak'),
            ExportColumn::make('work.name')
                ->label('Nama Paket'),
            ExportColumn::make('work.technical_team_string')
                ->label('Tim Teknis'),
            ExportColumn::make('work.procurementOfficer.name')
                ->label('Pejabat Pengadaan'),
            ExportColumn::make('work.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('work.village.name')
                ->label('Desa'),
            ExportColumn::make('rt')
                ->label('RT'),
            ExportColumn::make('length')
                ->label('Panjang (m)'),
            ExportColumn::make('width')
                ->label('Lebar (m)'),
            ExportColumn::make('phone')
                ->label('Telepon'),
            ExportColumn::make('construct_type')
                ->label('Konstruksi'),
            ExportColumn::make('lat')
                ->label('Latitude'),
            ExportColumn::make('lng')
                ->label('Longitude'),
            ExportColumn::make('progress_status')
                ->label('Progress Status'),
            ExportColumn::make('real_1')
                ->label('Progress Minggu 1'),
            ExportColumn::make('real_2')
                ->label('Progress Minggu 2'),
            ExportColumn::make('real_3')
                ->label('Progress Minggu 3'),
            ExportColumn::make('real_4')
                ->label('Progress Minggu 4'),
            ExportColumn::make('real_5')
                ->label('Progress Minggu 5'),
            ExportColumn::make('real_6')
                ->label('Progress Minggu 6'),
            ExportColumn::make('note')
                ->label('Catatan Konsultan'),
            ExportColumn::make('photo_0_url')
                ->label('Foto 1 URL'),
            ExportColumn::make('photo_50_url')
                ->label('Foto 2 URL'),
            ExportColumn::make('photo_100_url')
                ->label('Foto 3 URL'),
            ExportColumn::make('photo_pho_url')
                ->label('Foto 4 URL'),
            ExportColumn::make('shop_drawing_url')
                ->label('Shop Drawing URL'),
            ExportColumn::make('asbuilt_drawing_url')
                ->label('Asbuilt Drawing URL'),
            ExportColumn::make('rab_url')
                ->label('RAB URL'),
            ExportColumn::make('laporan_url')
                ->label('Laporan URL'),
            ExportColumn::make('file_shp_url')
                ->label('File SHP URL'),
            ExportColumn::make('file_konsultan_perencana_url')
                ->label('File Konsultan Perencana URL'),
            ExportColumn::make('file_konsultan_pengawas_url')
                ->label('File Konsultan Pengawas URL'),
            ExportColumn::make('file_kontraktor_pelaksana_url')
                ->label('File Kontraktor Pelaksana URL'),
        ];
    }

    public function headings(): array
    {
        $columns = collect(static::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        return $columns->map(function (ExportColumn $column) {
            return $column->getLabel() ?? $column->getName();
        })->toArray();
    }

    public function map($facility): array
    {
        $columns = collect(self::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        $row = $columns->map(function ($column) use ($facility) {
            $field = $column->getName();

            if ($field === 'no') {
                return self::$number++;
            }

            // Handle progress status enum
            if ($field === 'progress_status') {
                return $facility->progress_status ? $facility->progress_status->getLabel() : '';
            }

            // Handle relationship fields
            if ($field === 'work.technical_team_string') {
                return $facility->work ? $facility->work->technical_team_string : '';
            }

            if ($field === 'work.contract_number') {
                return $facility->work ? $facility->work->contract_number : '';
            }

            if ($field === 'work.name') {
                return $facility->work ? $facility->work->name : '';
            }

            if ($field === 'work.district.name') {
                return $facility->work && $facility->work->district ? $facility->work->district->name : '';
            }

            if ($field === 'work.village.name') {
                return $facility->work && $facility->work->village ? $facility->work->village->name : '';
            }

            if ($field === 'work.procurementOfficer.name') {
                return $facility->work && $facility->work->procurementOfficer ? $facility->work->procurementOfficer->name : '';
            }

            // Default field access
            return $facility->$field ?? '';
        })->toArray();

        return $row;
    }
}
