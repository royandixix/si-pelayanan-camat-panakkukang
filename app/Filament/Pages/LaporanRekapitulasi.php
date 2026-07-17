<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanRekapitulasi extends Page
{
    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup =
        'Analisis dan Laporan';

    protected static ?string $navigationLabel =
        'Laporan Rekapitulasi';

    protected static ?string $title =
        'Laporan Rekapitulasi';

    protected static ?string $slug =
        'laporan-rekapitulasi';

    protected static ?int $navigationSort = 2;

    protected string $view =
        'filament.pages.laporan-rekapitulasi';

    public function getHeading(): string
    {
        return 'Laporan Rekapitulasi Pelayanan';
    }

    public function getSubheading(): ?string
    {
        return 'Ringkasan data pelayanan masyarakat berdasarkan periode, seksi, layanan, dan status permohonan.';
    }
    
    public static function getNavigationBadge(): ?string
    {
        return null;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}