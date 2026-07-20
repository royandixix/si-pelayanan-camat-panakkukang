<?php

namespace App\Filament\Resources\ServiceApplications\Tables;

use App\Enums\ApplicationStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ServiceApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at','desc')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('Nama Masyarakat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service.section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('assignedAdmin.name')
                    ->label('Admin Seksi')
                    ->placeholder('Belum ditentukan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->label('Tanggal Diajukan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->label('Tanggal Diverifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('rejected_at')
                    ->label('Tanggal Ditolak')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Permohonan')
                    ->options(ApplicationStatus::class),
                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service','name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('assigned_admin_id')
                    ->label('Admin Seksi')
                    ->relationship('assignedAdmin','name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make()
                    ->label('Status Penghapusan')
                    ->placeholder('Semua Data')
                    ->trueLabel('Data Terhapus')
                    ->falseLabel('Data Aktif'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('Belum ada permohonan layanan')
            ->emptyStateDescription('Permohonan yang diajukan masyarakat akan ditampilkan pada halaman ini.')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}