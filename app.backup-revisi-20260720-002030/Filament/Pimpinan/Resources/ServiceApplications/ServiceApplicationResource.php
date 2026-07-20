<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications;

use App\Filament\Pimpinan\Resources\ServiceApplications\Pages\ListServiceApplications;
use App\Filament\Pimpinan\Resources\ServiceApplications\Pages\ViewServiceApplication;
use App\Filament\Pimpinan\Resources\ServiceApplications\RelationManagers\DocumentsRelationManager;
use App\Filament\Pimpinan\Resources\ServiceApplications\RelationManagers\StatusHistoriesRelationManager;
use App\Filament\Pimpinan\Resources\ServiceApplications\Schemas\ServiceApplicationForm;
use App\Filament\Pimpinan\Resources\ServiceApplications\Schemas\ServiceApplicationInfolist;
use App\Filament\Pimpinan\Resources\ServiceApplications\Tables\ServiceApplicationsTable;
use App\Models\ServiceApplication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ServiceApplicationResource extends Resource
{
    protected static ?string $model=ServiceApplication::class;

    protected static string|\BackedEnum|null $navigationIcon='heroicon-o-document-magnifying-glass';

    protected static string|\UnitEnum|null $navigationGroup='Monitoring Pelayanan';

    protected static ?string $navigationLabel='Monitoring Permohonan';

    protected static ?string $modelLabel='Permohonan';

    protected static ?string $pluralModelLabel='Monitoring Permohonan';

    protected static ?string $recordTitleAttribute='registration_number';

    protected static ?string $slug='monitoring-permohonan';

    protected static ?int $navigationSort=1;

    protected static bool $hasTitleCaseModelLabel=false;

    public static function form(Schema $schema): Schema
    {
        return ServiceApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
            StatusHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'=>ListServiceApplications::route('/'),
            'view'=>ViewServiceApplication::route('/{record}'),
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
