<?php

namespace App\Filament\Pages;

use App\Services\KMeansTestingService;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class PengujianKMeans extends Page
{
    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup =
        'Data Mining';

    protected static ?string $navigationLabel =
        'Pengujian K-Means';

    protected static ?string $title =
        'Pengujian K-Means';

    protected static ?string $slug =
        'pengujian-k-means';

    protected static ?int $navigationSort = 4;

    protected string $view =
        'filament.pages.pengujian-k-means';

    public array $evaluation = [];

    public function mount(): void
    {
        $this->evaluation = app(
            KMeansTestingService::class
        )->evaluate();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
