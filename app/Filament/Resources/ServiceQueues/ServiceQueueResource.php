<?php

namespace App\Filament\Resources\ServiceQueues;

use App\Filament\Resources\ServiceQueues\Pages\ListServiceQueues;
use App\Filament\Resources\ServiceQueues\Pages\ViewServiceQueue;
use App\Filament\Resources\ServiceQueues\Schemas\ServiceQueueForm;
use App\Filament\Resources\ServiceQueues\Schemas\ServiceQueueInfolist;
use App\Filament\Resources\ServiceQueues\Tables\ServiceQueuesTable;
use App\Models\ServiceQueue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceQueueResource extends Resource
{
    protected static ?string $model = ServiceQueue::class;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-queue-list';

    protected static string|\UnitEnum|null $navigationGroup =
        'Pelayanan';

    protected static ?string $navigationLabel =
        'Antrean Pelayanan';

    protected static ?string $modelLabel =
        'Antrean Pelayanan';

    protected static ?string $pluralModelLabel =
        'Antrean Pelayanan';

    protected static ?string $recordTitleAttribute =
        'queue_number';

    protected static ?string $slug =
        'antrean-pelayanan';

    protected static ?int $navigationSort = 2;

    protected static bool $hasTitleCaseModelLabel = false;

    public static function form(Schema $schema): Schema
    {
        return ServiceQueueForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ServiceQueueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceQueuesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'application',
                'user',
                'section',
                'service',
            ])
            ->orderByDesc('queue_date')
            ->orderBy('section_id')
            ->orderBy('service_id')
            ->orderBy('sequence')
            ->orderBy('registered_at');

        $user = auth()->user();

        if ($user?->isAdminSeksi()) {
            $query->where(
                'section_id',
                $user->section_id,
            );
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
        return false;
    }

    public static function canView($record): bool
    {
        $user = auth()->user();

        if ($user?->isSuperAdmin()) {
            return true;
        }

        return ($user?->isAdminSeksi() ?? false)
            && $user->section_id !== null
            && $record->section_id === $user->section_id;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceQueues::route('/'),
            'view' => ViewServiceQueue::route('/{record}'),
        ];
    }
}