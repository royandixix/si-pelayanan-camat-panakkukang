<?php

namespace App\Filament\Resources\KMeansRuns;

use App\Filament\Resources\KMeansRuns\Pages\ListKMeansRuns;
use App\Filament\Resources\KMeansRuns\Pages\ViewKMeansRun;
use App\Filament\Resources\KMeansRuns\Schemas\KMeansRunInfolist;
use App\Filament\Resources\KMeansRuns\Tables\KMeansRunsTable;
use App\Models\KMeansRun;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class KMeansRunResource extends Resource
{
    protected static ?string $model = KMeansRun::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup =
        'Data Mining';

    protected static ?string $navigationLabel =
        'Proses K-Means';

    protected static ?string $modelLabel =
        'Proses K-Means';

    protected static ?string $pluralModelLabel =
        'Proses K-Means';

    protected static ?string $recordTitleAttribute =
        'id';

    protected static ?string $slug =
        'proses-k-means';

    protected static ?int $navigationSort = 2;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
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

    public static function canView(Model $record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function shouldRegisterNavigation(): bool
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
            'view' => ViewKMeansRun::route('/{record}'),
        ];
    }
}
