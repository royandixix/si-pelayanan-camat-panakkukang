<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard Super Admin';

    public function getHeading(): string
    {
        return 'Dashboard Super Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Ringkasan pelayanan masyarakat Kantor Camat Panakkukang.';
    }

    public function getColumns(): int | array
    {
        return [
            'md' => 1,
            'xl' => 2,
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
