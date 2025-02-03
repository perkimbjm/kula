<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Pages\Auth\LoginScreenPage;

class AppPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login(LoginScreenPage::class)
            ->registration()
            ->profile(isSimple: false)
            ->simplePageMaxContentWidth(MaxWidth::Small)
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->navigationGroups(['Manajemen Proyek', 'Manajemen PBJ', 'Manajemen Usulan / Pengaduan', 'Manajemen Pengguna', 'Manajemen Lokasi', 'Manajemen Konten'])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,


            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->collapsedSidebarWidth('0')
            ->brandLogo(asset('/img/panel-logo.png'))
            ->darkModeBrandLogo(asset('/img/panel-logo-dark.png'))
            ->favicon(asset('/img/favicon/favicon.ico'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \Hydrat\TableLayoutToggle\TableLayoutTogglePlugin::make([
                    'defaultLayout' => 'grid',
                    'persistLayoutInLocalStorage' => true,
                    'shareLayoutBetweenPages' => false,
                    'displayToggleAction' => true,
                    'toggleActionHook' => 'tables::toolbar.search.after',
                    'listLayoutButtonIcon' => 'heroicon-o-list-bullet',
                    'gridLayoutButtonIcon' => 'heroicon-o-squares-2x2'
                ])
            ]);
    }
}
