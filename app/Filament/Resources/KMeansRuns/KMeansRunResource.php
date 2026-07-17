<?php

namespace App\Filament\Resources\KMeansRuns;

use App\Filament\Resources\KMeansRuns\Pages\CreateKMeansRun;
use App\Filament\Resources\KMeansRuns\Pages\EditKMeansRun;
use App\Filament\Resources\KMeansRuns\Pages\ListKMeansRuns;
use App\Filament\Resources\KMeansRuns\Pages\ViewKMeansRun;
use App\Filament\Resources\KMeansRuns\Schemas\KMeansRunForm;
use App\Filament\Resources\KMeansRuns\Schemas\KMeansRunInfolist;
use App\Filament\Resources\KMeansRuns\Tables\KMeansRunsTable;
use App\Models\KMeansRun;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class KMeansRunResource extends Resource
{
    protected static ?string $model = KMeansRun::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar-square';

    protected static string|\UnitEnum|null $navigationGroup =
        'Analisis dan Laporan';

    protected static ?string $navigationLabel =
        'Proses K-Means';

    protected static ?string $modelLabel =
        'Proses Klasterisasi';

    protected static ?string $pluralModelLabel =
        'Proses K-Means';

    protected static ?string $recordTitleAttribute =
        'id';

    protected static ?string $slug =
        'proses-k-means';

    protected static ?int $navigationSort = 1;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return KMeansRunForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KMeansRunInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KMeansRunsTable::configure($table);
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canView($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKMeansRuns::route('/'),
            'create' => CreateKMeansRun::route('/buat'),
            'view' => ViewKMeansRun::route('/{record}'),
            'edit' => EditKMeansRun::route('/{record}/ubah'),
        ];
    }
}