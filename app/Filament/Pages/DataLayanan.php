<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DataLayanan extends Page
{
    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-document-check';

    protected static string|\UnitEnum|null $navigationGroup =
        'Data Utama';

    protected static ?string $navigationLabel =
        'Data Layanan';

    protected static ?string $title =
        'Data Layanan';

    protected static ?string $slug =
        'data-layanan';

    protected static ?int $navigationSort = 2;

    protected string $view =
        'filament.pages.data-layanan';

    public function getHeading(): string
    {
        return 'Data Layanan';
    }

    public function getSubheading(): ?string
    {
        return 'Data pelayanan aktif dan data historis pelayanan Kecamatan Panakkukang.';
    }

    public static function canAccess(): bool
    {
        return auth('admin')->user()?->isSuperAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth('admin')->user()?->isSuperAdmin() ?? false;
    }
}
