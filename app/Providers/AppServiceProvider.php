<?php

namespace App\Providers;

use App\Models\User;
use Livewire\Livewire;
use Filament\Facades\Filament;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;
use App\Filament\Widgets\StatsOverview;
use Illuminate\Support\ServiceProvider;
// use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        User::observe(UserObserver::class);


        Livewire::component('filament.widgets.stats-overview', StatsOverview::class);

    }
}
