<?php

namespace App\Providers;

use App\Models\User;
use Livewire\Livewire;
use Filament\Facades\Filament;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\URL;
use App\Filament\Widgets\StatsOverview;
use Illuminate\Support\ServiceProvider;
use App\Filament\Pages\Auth\LoginScreenPage;
use Illuminate\Auth\Events;
use Illuminate\Support\Facades\Event;
use App\Services\CustomAuditor;
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
        Livewire::component('app.filament.pages.auth.login-screen-page', LoginScreenPage::class);

        // Register audit events for authentication
        Event::listen([
            Events\Login::class,
            Events\Logout::class,
            Events\Failed::class,
            Events\Lockout::class,
        ], function ($event) {
            $action = class_basename($event);
            CustomAuditor::record($action, [], 'Auth\\' . $action, $event->user->id ?? null);
        });
    }
}
