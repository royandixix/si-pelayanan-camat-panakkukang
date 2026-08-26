<?php

namespace App\Filament\Resources\ResearchDatasetRecords;

use App\Filament\Resources\ResearchDatasetRecords\Pages\ListResearchDatasetRecords;
use App\Filament\Resources\ResearchDatasetRecords\Pages\ViewResearchDatasetRecord;
use App\Filament\Resources\ResearchDatasetRecords\Schemas\ResearchDatasetRecordForm;
use App\Filament\Resources\ResearchDatasetRecords\Schemas\ResearchDatasetRecordInfolist;
use App\Filament\Resources\ResearchDatasetRecords\Tables\ResearchDatasetRecordsTable;
use App\Models\ResearchDatasetRecord;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ResearchDatasetRecordResource extends Resource
{
    protected static ?string $model = ResearchDatasetRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Data Mining';

    protected static ?string $navigationLabel = 'Dataset Penelitian';

    protected static ?string $modelLabel = 'Dataset Penelitian';

    protected static ?string $pluralModelLabel = 'Dataset Penelitian';

    protected static ?string $recordTitleAttribute = 'dataset_name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ResearchDatasetRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ResearchDatasetRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResearchDatasetRecordsTable::configure($table);
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
        $role = Filament::auth()->user()?->role;

        if ($role instanceof BackedEnum) {
            $role = $role->value;
        }

        return $role === 'super_admin';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchDatasetRecords::route('/'),
            'view' => ViewResearchDatasetRecord::route('/{record}'),
        ];
    }
}
