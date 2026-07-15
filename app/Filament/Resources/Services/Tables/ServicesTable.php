<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('code')
                    ->label('Kode Layanan')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('queue_enabled')
                    ->label('Antrean Digital')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('processing_days')
                    ->label('Estimasi Penyelesaian')
                    ->numeric()
                    ->suffix(' hari')
                    ->placeholder('Tidak ditentukan')
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
                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('queue_enabled')
                    ->label('Antrean Digital')
                    ->trueLabel('Menggunakan Antrean')
                    ->falseLabel('Tanpa Antrean')
                    ->placeholder('Semua Layanan'),

                TernaryFilter::make('is_active')
                    ->label('Status Layanan')
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
                        ->modalHeading('Hapus data layanan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus data layanan yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])
                    ->label('Tindakan'),
            ])
            ->emptyStateHeading('Belum ada data layanan')
            ->emptyStateDescription('Tambahkan jenis layanan yang tersedia di Kantor Camat Panakkukang.')
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }
}