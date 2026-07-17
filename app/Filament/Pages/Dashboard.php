<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    protected static ?int $navigationSort = -2;

    public function getHeading(): string
    {
        $user = auth()->user();

        if ($user?->isAdminSeksi()) {
            return 'Dashboard Admin Seksi';
        }

        return 'Dashboard Super Admin';
    }

    public function getSubheading(): ?string
    {
        $user = auth()->user();

        if ($user?->isAdminSeksi()) {
            return 'Ringkasan pelayanan pada ' . ($user->section?->name ?? 'seksi Anda') . '.';
        }

        return 'Ringkasan pelayanan masyarakat Kantor Camat Panakkukang.';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return ($user?->isSuperAdmin() ?? false)
            || ($user?->isAdminSeksi() ?? false);
    }
}