<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\ApplicationStatusChart;
use App\Filament\Widgets\ServiceVolumeChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getHeading(): string
    {
        return 'Dashboard Super Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Ringkasan pelayanan masyarakat Kantor Camat Panakkukang.';
    }

    public function getColumns(): int|array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            ServiceVolumeChart::class,
            ApplicationStatusChart::class,
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
