<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults;

use App\Filament\Pimpinan\Resources\KMeansResults\Pages\ListKMeansResults;
use App\Filament\Pimpinan\Resources\KMeansResults\Pages\ViewKMeansResult;
use App\Filament\Pimpinan\Resources\KMeansResults\Schemas\KMeansResultInfolist;
use App\Filament\Pimpinan\Resources\KMeansResults\Tables\KMeansResultsTable;
use App\Models\KMeansResult;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KMeansResultResource extends Resource
{
    protected static ?string $model = KMeansResult::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-chart-bar-square';

    protected static string|\UnitEnum|null $navigationGroup =
        'Analisis dan Keputusan';

    protected static ?string $navigationLabel =
        'Analisis K-Means';

    protected static ?string $modelLabel =
        'Hasil K-Means';

    protected static ?string $pluralModelLabel =
        'Analisis K-Means';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'analisis-k-means';

    protected static ?int $navigationSort = 1;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return $schema;
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
        return parent::getEloquentQuery()
            ->with([
                'section',
                'run',
                'run.executor',
            ])
            ->orderByDesc('k_means_run_id')
            ->orderBy('rank');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKMeansResults::route('/'),
            'view' => ViewKMeansResult::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        return true;
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
}
