<?php

namespace App\Filament\Resources\Sections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('code')
                    ->label('Kode Seksi')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('daily_queue_quota')
                    ->label('Kuota Antrean Harian')
                    ->numeric()
                    ->placeholder('Tidak ada')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua Status'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),

                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->modalHeading('Hapus data seksi')
                        ->modalDescription('Apakah Anda yakin ingin menghapus data seksi yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])
                    ->label('Tindakan'),
            ])
            ->emptyStateHeading('Belum ada data seksi')
            ->emptyStateDescription('Tambahkan data seksi untuk mengelola layanan Kantor Camat Panakkukang.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}