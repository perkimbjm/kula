<?php

namespace App\Filament\Widgets;

use App\Models\Facility;
use App\Enums\ProgressStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;

class WorkScoreboard extends ChartWidget
{
    use HasWidgetShield;

    protected static array $allowedRoles = [1];

    public static function canView(): bool
    {
        return Auth::check() && in_array(Auth::user()->role_id, static::$allowedRoles);
    }

    protected static ?string $heading = 'Progres Pekerjaan Fisik';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'Last Year',
        ];
    }

    protected function getData(): array
    {
        $query = Facility::query();

        if ($this->filter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('created_at', [now()->subWeek(), now()]);
        } elseif ($this->filter === 'month') {
            $query->whereBetween('created_at', [now()->subMonth(), now()]);
        } elseif ($this->filter === 'year') {
            $query->whereBetween('created_at', [now()->subYear(), now()]);
        }

        $totalFacilities = $query->count();
        $completedFacilities = $query->where('progress_status', ProgressStatus::SELESAI)->count();
        $inProgressFacilities = $query->where('progress_status', ProgressStatus::BERJALAN)->count();
        $criticalFacilities = $query->where('progress_status', ProgressStatus::KRITIS)->count();

        $progressPercentage = $totalFacilities > 0 ? ($completedFacilities / $totalFacilities) * 100 : 0;

        return [
            'datasets' => [
                [
                    'label' => 'Status Pekrjaan Fisik',
                    'data' => [$completedFacilities, $inProgressFacilities, $criticalFacilities],
                ],
            ],
            'labels' => ['Selesai', 'Berjalan', 'Kritis'],
            'meta' => [
                'totalFacilities' => $totalFacilities,
                'completedFacilities' => $completedFacilities,
                'inProgressFacilities' => $inProgressFacilities,
                'criticalFacilities' => $criticalFacilities,
                'progressPercentage' => $progressPercentage,
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed !== null) {
                                label += context.parsed;
                            }
                            return label;
                        }',
                    ],
                ],
            ],
        ];
    }

    // Change this method to public
    public function getHeading(): string
    {
        return 'Progres Pekerjaan Fisik';
    }

    protected function getSubheading(): ?string
    {
        return 'Progress and Status Overview';
    }

    protected function getFooter(): ?string
    {
        $meta = $this->getData()['meta'];
        return "Total Laporan : {$meta['totalFacilities']} | Selesai: {$meta['completedFacilities']} | Berjalan: {$meta['inProgressFacilities']} | Kritis: {$meta['criticalFacilities']} | Progress: {$meta['progressPercentage']}%";
    }
}
