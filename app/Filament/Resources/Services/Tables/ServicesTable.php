<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('section.name')
                    ->label('Seksi / Divisi')
                    ->collapsible(),
            ])
            ->defaultGroup('section.name')
            ->columns([
                TextColumn::make('section.name')
                    ->label('Seksi Penanggung Jawab')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('queue_enabled')
                    ->label('Antrean')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string =>
                            $state ? 'Antrean Digital' : 'Tanpa Antrean'
                    )
                    ->color(
                        fn ($state): string =>
                            $state ? 'success' : 'gray'
                    ),

                TextColumn::make('processing_days')
                    ->label('Estimasi')
                    ->numeric()
                    ->suffix(' hari')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string =>
                            $state ? 'Aktif' : 'Tidak Aktif'
                    )
                    ->color(
                        fn ($state): string =>
                            $state ? 'success' : 'danger'
                    ),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(
                        fn (): bool =>
                            auth()->user()?->isSuperAdmin() ?? false
                    ),

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
                    ->label('Ubah')
                    ->visible(
                        fn (): bool =>
                            auth()->user()?->isSuperAdmin() ?? false
                    ),
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
                    ->label('Tindakan')
                    ->visible(
                        fn (): bool =>
                            auth()->user()?->isSuperAdmin() ?? false
                    ),
            ])
            ->emptyStateHeading('Belum ada data layanan')
            ->emptyStateDescription(
                'Belum ada layanan yang tersedia pada seksi ini.'
            )
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }
}
