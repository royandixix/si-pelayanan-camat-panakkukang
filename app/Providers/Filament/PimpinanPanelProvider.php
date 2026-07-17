<?php

namespace App\Providers\Filament;

use App\Filament\Pimpinan\Widgets\PimpinanApplicationStatusChart;
use App\Filament\Pimpinan\Widgets\PimpinanSectionWorkloadChart;
use App\Filament\Pimpinan\Widgets\PimpinanStatsOverview;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PimpinanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pimpinan')
            ->path('pimpinan')
            ->login()
            ->brandName('Pelayanan Camat Panakkukang')
            ->defaultThemeMode(ThemeMode::System)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path('Filament/Pimpinan/Resources'),
                for: 'App\\Filament\\Pimpinan\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pimpinan/Pages'),
                for: 'App\\Filament\\Pimpinan\\Pages',
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Pimpinan/Widgets'),
                for: 'App\\Filament\\Pimpinan\\Widgets',
            )
            ->widgets([
                PimpinanStatsOverview::class,
                PimpinanApplicationStatusChart::class,
                PimpinanSectionWorkloadChart::class,
            ])
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
            ]);
    }
}