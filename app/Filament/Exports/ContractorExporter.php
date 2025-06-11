<?php

namespace App\Filament\Exports;

use App\Models\Contractor;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Filament\Actions\Exports\ExportColumn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContractorExporter implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Contractor::query();

        // Apply any filters if needed
        // Add filtering logic here based on table filters if ContractorResource has filters

        return $query;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no')
                ->label('No'),
            ExportColumn::make('name')
                ->label('Penyedia Jasa'),
            ExportColumn::make('akta')
                ->label('No. Akta Terakhir'),
            ExportColumn::make('founding_date')
                ->label('Tanggal'),
            ExportColumn::make('notary')
                ->label('Notaris'),
            ExportColumn::make('address')
                ->label('Alamat Penyedia'),
            ExportColumn::make('npwp')
                ->label('NPWP'),
            ExportColumn::make('leader')
                ->label('Nama Direktur'),
            ExportColumn::make('position')
                ->label('Jabatan'),
            ExportColumn::make('bank')
                ->label('Bank'),
            ExportColumn::make('account_number')
                ->label('Nomor Rekening'),
            ExportColumn::make('account_holder')
                ->label('Nama Nasabah'),
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

    public function map($contractor): array
    {
        $columns = collect(self::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        $row = $columns->map(function ($column) use ($contractor) {
            $field = $column->getName();

            if ($field === 'no') {
                return self::$number++;
            }

            if ($field === 'founding_date') {
                return $contractor->founding_date ? $contractor->founding_date->format('d-m-Y') : '';
            }

            return $contractor->$field ?? null;
        })->toArray();

        return $row;
    }
}
