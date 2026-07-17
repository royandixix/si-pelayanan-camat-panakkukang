<?php

namespace App\Filament\Pimpinan\Resources\ServiceReports;

use App\Filament\Pimpinan\Resources\ServiceReports\Pages\ListServiceReports;
use App\Filament\Pimpinan\Resources\ServiceReports\Tables\ServiceReportsTable;
use App\Models\ServiceApplication;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ServiceReportResource extends Resource
{
    protected static ?string $model=ServiceApplication::class;

    protected static string|\BackedEnum|null $navigationIcon='heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup='Laporan';

    protected static ?string $navigationLabel='Laporan Pelayanan';

    protected static ?string $modelLabel='Laporan Pelayanan';

    protected static ?string $pluralModelLabel='Laporan Pelayanan';

    protected static ?string $recordTitleAttribute='application_number';

    protected static ?string $slug='laporan-pelayanan';

    protected static ?int $navigationSort=1;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return ServiceReportsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'=>ListServiceReports::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        return false;
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