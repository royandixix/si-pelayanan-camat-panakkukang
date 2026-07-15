<?php

namespace App\Filament\Resources\ServiceRequirements;

use App\Filament\Resources\ServiceRequirements\Pages\CreateServiceRequirement;
use App\Filament\Resources\ServiceRequirements\Pages\EditServiceRequirement;
use App\Filament\Resources\ServiceRequirements\Pages\ListServiceRequirements;
use App\Filament\Resources\ServiceRequirements\Pages\ViewServiceRequirement;
use App\Filament\Resources\ServiceRequirements\Schemas\ServiceRequirementForm;
use App\Filament\Resources\ServiceRequirements\Schemas\ServiceRequirementInfolist;
use App\Filament\Resources\ServiceRequirements\Tables\ServiceRequirementsTable;
use App\Models\ServiceRequirement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ServiceRequirementResource extends Resource
{
    protected static ?string $model = ServiceRequirement::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup =
        'Data Utama';

    protected static ?string $navigationLabel =
        'Persyaratan Layanan';

    protected static ?string $modelLabel =
        'Persyaratan Layanan';

    protected static ?string $pluralModelLabel =
        'Persyaratan Layanan';

    protected static ?string $recordTitleAttribute =
        'name';

    protected static ?string $slug =
        'persyaratan-layanan';

    protected static ?int $navigationSort = 3;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return ServiceRequirementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceRequirementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceRequirementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceRequirements::route('/'),
            'create' => CreateServiceRequirement::route('/buat'),
            'view' => ViewServiceRequirement::route('/{record}'),
            'edit' => EditServiceRequirement::route('/{record}/ubah'),
        ];
    }
}