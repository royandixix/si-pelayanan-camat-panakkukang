<?php

namespace App\Filament\Resources\ServiceApplications;

use App\Filament\Resources\ServiceApplications\Pages\ListServiceApplications;
use App\Filament\Resources\ServiceApplications\Pages\ViewServiceApplication;
use App\Filament\Resources\ServiceApplications\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\ServiceApplications\RelationManagers\StatusHistoriesRelationManager;
use App\Filament\Resources\ServiceApplications\Schemas\ServiceApplicationForm;
use App\Filament\Resources\ServiceApplications\Schemas\ServiceApplicationInfolist;
use App\Filament\Resources\ServiceApplications\Tables\ServiceApplicationsTable;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;


class ServiceApplicationResource extends Resource
{
    protected static ?string $model = ServiceApplication::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Pelayanan';
    protected static ?string $navigationLabel = 'Permohonan Layanan';
    protected static ?string $modelLabel = 'Permohonan Layanan';
    protected static ?string $pluralModelLabel = 'Permohonan Layanan';
    protected static ?string $recordTitleAttribute = 'registration_number';
    protected static ?string $slug = 'permohonan-layanan';
    protected static ?int $navigationSort = 1;
    protected static bool $hasTitleCaseModelLabel = false;

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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with([
                'user',
                'service.section',
                'assignedAdmin',
                'documents.requirement',
            ]);

        $user = Auth::user();

        if($user instanceof User && $user->isAdminSeksi()){
            $query->whereHas(
                'service',
                fn(Builder $serviceQuery): Builder => $serviceQuery
                    ->where('section_id',$user->section_id),
            );
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        if(!$user instanceof User){
            return false;
        }

        return $user->isSuperAdmin() || $user->isAdminSeksi();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        $user = Auth::user();

        if(!$user instanceof User || !$record instanceof ServiceApplication){
            return false;
        }

        if($user->isSuperAdmin()){
            return true;
        }

        return $user->isAdminSeksi()
            &&$user->section_id!==null
            &&$record->service?->section_id===$user->section_id;
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

    public static function canForceDelete(Model $record): bool
    {
        return false;
    }

    public static function canForceDeleteAny(): bool
    {
        return false;
    }

    public static function canRestore(Model $record): bool
    {
        return false;
    }

    public static function canRestoreAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index'=>ListServiceApplications::route('/'),
            'view'=>ViewServiceApplication::route('/{record}'),
        ];
    }
}