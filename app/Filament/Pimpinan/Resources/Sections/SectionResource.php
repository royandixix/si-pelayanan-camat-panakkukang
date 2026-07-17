<?php

namespace App\Filament\Pimpinan\Resources\Sections;

use App\Filament\Pimpinan\Resources\Sections\Pages\ListSections;
use App\Filament\Pimpinan\Resources\Sections\Pages\ViewSection;
use App\Filament\Pimpinan\Resources\Sections\Schemas\SectionForm;
use App\Filament\Pimpinan\Resources\Sections\Tables\SectionsTable;
use App\Models\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SectionResource extends Resource
{
    protected static ?string $model=Section::class;

    protected static string|\BackedEnum|null $navigationIcon='heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup='Monitoring Pelayanan';

    protected static ?string $navigationLabel='Data Seksi';

    protected static ?string $modelLabel='Seksi';

    protected static ?string $pluralModelLabel='Data Seksi';

    protected static ?string $recordTitleAttribute='name';

    protected static ?string $slug='data-seksi';

    protected static ?int $navigationSort=2;

    public static function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'=>ListSections::route('/'),
            'view'=>ViewSection::route('/{record}'),
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