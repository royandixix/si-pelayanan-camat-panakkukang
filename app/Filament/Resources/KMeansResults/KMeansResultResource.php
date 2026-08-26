<?php

namespace App\Filament\Resources\KMeansResults;

use App\Filament\Resources\KMeansResults\Pages\EditKMeansResult;
use App\Filament\Resources\KMeansResults\Pages\ListKMeansResults;
use App\Filament\Resources\KMeansResults\Pages\ViewKMeansResult;
use App\Filament\Resources\KMeansResults\Schemas\KMeansResultForm;
use App\Filament\Resources\KMeansResults\Schemas\KMeansResultInfolist;
use App\Filament\Resources\KMeansResults\Tables\KMeansResultsTable;
use App\Models\KMeansResult;
use App\Models\KMeansRun;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class KMeansResultResource extends Resource
{
    protected static ?string $model = KMeansResult::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-squares-2x2';

    protected static string|UnitEnum|null $navigationGroup =
        'Data Mining';

    protected static ?string $navigationLabel =
        'Hasil Clustering';

    protected static ?string $modelLabel =
        'Hasil Clustering';

    protected static ?string $pluralModelLabel =
        'Hasil Clustering';

    protected static ?string $recordTitleAttribute =
        'dataset_name';

    protected static ?string $slug =
        'hasil-clustering';

    protected static ?int $navigationSort = 3;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return KMeansResultForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KMeansResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KMeansResultsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $latestRunId = KMeansRun::query()
            ->latest('id')
            ->value('id');

        return parent::getEloquentQuery()
            ->when(
                $latestRunId,
                fn (Builder $query) =>
                    $query->where('kmeans_run_id', $latestRunId),
                fn (Builder $query) =>
                    $query->whereRaw('1 = 0')
            );
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
        return auth()->user()?->isSuperAdmin() ?? false;
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
            'index' => ListKMeansResults::route('/'),
            'view' => ViewKMeansResult::route('/{record}'),
            'edit' => EditKMeansResult::route('/{record}/label-referensi'),
        ];
    }
}
