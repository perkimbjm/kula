<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WorkScoreboardWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
// use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget;

class Dasbor extends Page
{
    use HasPageShield;
    
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dasbor';

    protected function getWidgets(): array
    {
        return [
            AccountWidget::class,
            StatsOverview::class,
            WorkScoreboardWidget::class,
            // AdvancedStatsOverviewWidget::class
        ];
    }

    
}
