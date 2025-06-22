<?php

namespace App\Filament\Exports;

use App\Models\Work;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Filament\Actions\Exports\ExportColumn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FilteredWorkExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Work::query()->with(['contractor', 'consultant', 'supervisor']);

        if (isset($this->filters['contractor']['value'])) {
            $query->where('contractor_id', $this->filters['contractor']['value']);
        }

        return $query;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no')
                ->label('No'),
            ExportColumn::make('year')
                ->label('Tahun'),
            ExportColumn::make('name')
                ->label('Nama Paket'),
            ExportColumn::make('contract_date')
                ->label('Tanggal Kontrak'),
            ExportColumn::make('contract_number')
                ->label('Nomor Kontrak'),
            ExportColumn::make('contract_value')
                ->label('Nilai Kontrak'),
            ExportColumn::make('contractor.name')
                ->label('Kontraktor'),
            ExportColumn::make('consultant.name')
                ->label('Konsultan Perencana'),
            ExportColumn::make('supervisor.name')
                ->label('Konsultan Pengawas'),
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

    public function map($work): array
    {
        $columns = collect(self::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        $row = $columns->map(function ($column) use ($work) {
            $field = $column->getName();

            if ($field === 'no') {
                return self::$number++; // Gunakan nomor yang dipertahankan
            }

            if (str_contains($field, '.')) {
                $relations = explode('.', $field);
                $value = $work;
                foreach ($relations as $relation) {
                    $value = $value->$relation ?? null;
                }
                return $value;
            }

            if ($field === 'contract_date') {
                return $work->$field ? $work->$field->format('d-m-Y') : '';
            }

            return $work->$field ?? null;
        })->toArray();

        return $row;
    }
}
