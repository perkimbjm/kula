<?php

namespace App\Filament\Exports;

use App\Models\Village;
use App\Models\District;
use App\Models\Facility;
use Filament\Forms\Components\Select;
use Filament\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Filament\Actions\Exports\ExportColumn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Filament\Actions\Exports\Models\Export;

class FacilityExporter implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $filters;
    protected $selectedColumns;

    public function __construct(array $filters = [], array $selectedColumns = [])
    {
        $this->filters = $filters;
        $this->selectedColumns = $selectedColumns;
    }

    public function query()
    {
        $query = Facility::query()->with(['contractor', 'consultant', 'district', 'village']);
        
        if (isset($this->filters['contractor']['value'])) {
            $query->where('contractor_id', $this->filters['contractor']['value']);
        }

        if (isset($this->filters['district_id']['value'])) {
            $query->where('district_id', $this->filters['district_id']['value']);
        }
    
        if (isset($this->filters['village_id']['value'])) {
            $query->where('village_id', $this->filters['village_id']['value']);
        }
        
        return $query;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nama'),
            ExportColumn::make('contractor.name')
                ->label('Kontraktor'),
            ExportColumn::make('consultant.name')
                ->label('Konsultan'),
            ExportColumn::make('district.name')
                ->label('Kecamatan'),
            ExportColumn::make('village.name')
                ->label('Kelurahan / Desa'),
            ExportColumn::make('length')
                ->label('Panjang (m)'),
            ExportColumn::make('width')
                ->label('Lebar'),
            ExportColumn::make('lat'),
            ExportColumn::make('lng'),
            ExportColumn::make('real_1')
                ->label('Realisasi Fisik Pekan ke-1'),
            ExportColumn::make('real_2')
                ->label('Realisasi Fisik Pekan ke-2'),
            ExportColumn::make('real_3')
                ->label('Realisasi Fisik Pekan ke-3'),
            ExportColumn::make('real_4')
                ->label('Realisasi Fisik Pekan ke-4'),
            ExportColumn::make('real_5')
                ->label('Realisasi Fisik Pekan ke-5'),
            ExportColumn::make('real_6')
                ->label('Realisasi Fisik Pekan ke-6'),
            ExportColumn::make('real_7')
                ->label('Realisasi Fisik Pekan ke-7'),
            ExportColumn::make('real_8')
                ->label('Realisasi Fisik Pekan ke-8'),
            ExportColumn::make('note')
                ->label('Catatan Pengawas'),
            ExportColumn::make('note_pho')
                ->label('Catatan PHO'),
            ExportColumn::make('team')
                ->label('Tim PHO'),
            ExportColumn::make('construct_type')
                ->label('Jenis Konstruksi'),
            ExportColumn::make('spending_type')
                ->label('Jenis Pembayaran'),
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

    public function map($row): array
    {
        $columns = collect(self::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        return $columns->map(function ($column) use ($row) {
            $field = $column->getName();

            // Jika kolom adalah 'team' dan datanya array, ubah menjadi string dipisahkan koma
            if ($field === 'team' && is_array($row->$field)) {
                return implode(', ', $row->$field);
            }

            if (str_contains($field, '.')) {
                $relations = explode('.', $field);
                $value = $row;
                foreach ($relations as $relation) {
                    $value = $value->$relation ?? null;
                }
                return $value;
            }

            return $row->$field ?? null;
        })->toArray();
    }

    
}