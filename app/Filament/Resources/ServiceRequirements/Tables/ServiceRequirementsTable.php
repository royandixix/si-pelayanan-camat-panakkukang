<?php

namespace App\Filament\Resources\ServiceRequirements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServiceRequirementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('service.section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('name')
                    ->label('Nama Persyaratan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('allowed_extensions')
                    ->label('Ekstensi Berkas')
                    ->badge()
                    ->separator(',')
                    ->placeholder('-'),

                TextColumn::make('max_size_kb')
                    ->label('Ukuran Maksimal')
                    ->numeric()
                    ->suffix(' KB')
                    ->sortable(),

                IconColumn::make('is_required')
                    ->label('Wajib')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
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
                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_required')
                    ->label('Status Persyaratan')
                    ->trueLabel('Wajib Diunggah')
                    ->falseLabel('Tidak Wajib')
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
                        ->modalHeading('Hapus persyaratan layanan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus persyaratan layanan yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])
                    ->label('Tindakan'),
            ])
            ->emptyStateHeading('Belum ada persyaratan layanan')
            ->emptyStateDescription('Tambahkan persyaratan dokumen untuk setiap jenis layanan.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }
}