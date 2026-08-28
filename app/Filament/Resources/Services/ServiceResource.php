<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Pages\ViewService;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Schemas\ServiceInfolist;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup =
        'Data Utama';

    protected static ?string $navigationLabel = 'Master Layanan';

    protected static ?string $modelLabel =
        'Layanan';

    protected static ?string $pluralModelLabel =
        'Data Layanan';

    protected static ?string $recordTitleAttribute =
        'name';

    protected static ?string $slug =
        'layanan';

    protected static ?int $navigationSort = 2;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with('section');

        $user = auth()->user();

        if ($user?->isAdminSeksi()) {
            $query->where('section_id', $user->section_id);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return ($user?->isSuperAdmin() ?? false)
            || ($user?->isAdminSeksi() ?? false);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return ($user?->isSuperAdmin() ?? false)
            || (
                ($user?->isAdminSeksi() ?? false)
                && $user->section_id !== null
            );
    }

    public static function canView($record): bool
    {
        $user = auth()->user();

        if ($user?->isSuperAdmin()) {
            return true;
        }

        return ($user?->isAdminSeksi() ?? false)
            && $record->section_id === $user->section_id;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if ($user?->isSuperAdmin()) {
            return true;
        }

        return ($user?->isAdminSeksi() ?? false)
            && $user->section_id !== null
            && $record->section_id === $user->section_id;
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/buat'),
            'view' => ViewService::route('/{record}'),
            'edit' => EditService::route('/{record}/ubah'),
        ];
    }
}