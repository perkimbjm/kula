<?php

namespace App\Filament\Exports;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Filament\Actions\Exports\ExportColumn;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlanExporter implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
        $query = Plan::query()->with(['procurementOfficer', 'consultant']);

        // Apply filters based on table filters
        if (isset($this->filters['year']['value'])) {
            $query->where('year', $this->filters['year']['value']);
        }

        if (isset($this->filters['consultant']['value'])) {
            $query->where('consultant_id', $this->filters['consultant']['value']);
        }

        if (isset($this->filters['procurement_officer']['value'])) {
            $query->where('procurement_officer_id', $this->filters['procurement_officer']['value']);
        }

        return $query;
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('no')
                ->label('No'),
            ExportColumn::make('contract_number')
                ->label('Nomor Kontrak'),
            ExportColumn::make('program')
                ->label('Paket'),
            ExportColumn::make('procurementOfficer.name')
                ->label('Pejabat Pengadaan'),
            ExportColumn::make('duration')
                ->label('Waktu Pelaksanaan'),
            ExportColumn::make('oe')
                ->label('HPS'),
            ExportColumn::make('bid_value')
                ->label('Penawaran'),
            ExportColumn::make('correction_value')
                ->label('Aritmatik'),
            ExportColumn::make('nego_value')
                ->label('Harga SPK'),
            ExportColumn::make('consultant.name')
                ->label('Penyedia/Perusahaan'),
            ExportColumn::make('invite_date')
                ->label('Tanggal Undangan'),
            ExportColumn::make('evaluation_date')
                ->label('Tanggal Evaluasi'),
            ExportColumn::make('nego_date')
                ->label('Tanggal Negosiasi'),
            ExportColumn::make('BAHPL_date')
                ->label('Tanggal BA-HPL'),
            ExportColumn::make('sppbj_date')
                ->label('Tanggal SPPBJ'),
            ExportColumn::make('spk_date')
                ->label('Tanggal SPK'),
            ExportColumn::make('account_type')
                ->label('Sumber Dana'),
            ExportColumn::make('year')
                ->label('Tahun'),
            ExportColumn::make('addendum_number')
                ->label('Nomor Addendum'),
            ExportColumn::make('payment_date')
                ->label('Tanggal'),
            ExportColumn::make('payment_value')
                ->label('Nilai'),
            ExportColumn::make('ba_lkpp')
                ->label('BA LKPP'),
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

    public function map($plan): array
    {
        $columns = collect(self::getColumns());

        if (!empty($this->selectedColumns)) {
            $columns = $columns->filter(function ($column) {
                return in_array($column->getName(), $this->selectedColumns);
            });
        }

        $row = $columns->map(function ($column) use ($plan) {
            $field = $column->getName();

            if ($field === 'no') {
                return self::$number++;
            }

            // Handle date fields
            $dateFields = [
                'invite_date', 'evaluation_date', 'nego_date', 'BAHPL_date',
                'sppbj_date', 'spk_date', 'payment_date'
            ];

            if (in_array($field, $dateFields)) {
                return $plan->$field ? $plan->$field->format('d-m-Y') : '';
            }

            // Handle relationship fields
            if ($field === 'procurementOfficer.name') {
                return $plan->procurementOfficer ? $plan->procurementOfficer->name : '';
            }

            if ($field === 'consultant.name') {
                return $plan->consultant ? $plan->consultant->name : '';
            }

            // Handle numeric fields with formatting
            $numericFields = ['oe', 'bid_value', 'correction_value', 'nego_value', 'payment_value'];
            if (in_array($field, $numericFields)) {
                return $plan->$field ? number_format($plan->$field, 0, ',', '.') : '';
            }

            return $plan->$field ?? '';
        })->toArray();

        return $row;
    }
}
