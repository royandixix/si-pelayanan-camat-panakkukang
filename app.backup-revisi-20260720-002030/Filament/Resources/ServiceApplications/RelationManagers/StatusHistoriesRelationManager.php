<?php

namespace App\Filament\Resources\ServiceApplications\RelationManagers;

use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';
    protected static ?string $title = 'Riwayat Status';
    protected static ?string $modelLabel = 'Riwayat Status';
    protected static ?string $pluralModelLabel = 'Riwayat Status';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('from_status')
                    ->label('Status Sebelumnya')
                    ->badge()
                    ->placeholder('Awal'),
                TextColumn::make('to_status')
                    ->label('Status Baru')
                    ->badge(),
                TextColumn::make('changer.name')
                    ->label('Diubah Oleh')
                    ->placeholder('Sistem'),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Waktu Perubahan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([])
            ->emptyStateHeading('Belum ada riwayat status')
            ->emptyStateDescription('Riwayat perubahan status permohonan akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    public static function canViewForRecord(Model $ownerRecord,string $pageClass): bool
    {
        $user = Auth::user();

        if(!$user instanceof User){
            return false;
        }

        if($user->isSuperAdmin()){
            return true;
        }

        return $user->isAdminSeksi()
            &&$user->section_id!==null
            &&$ownerRecord->service?->section_id===$user->section_id;
    }
}