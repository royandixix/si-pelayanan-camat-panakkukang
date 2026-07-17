<?php

namespace App\Filament\Pimpinan\Resources\Sections\Tables;

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
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Seksi')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->color('primary'),

                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(80)
                    ->wrap()
                    ->placeholder('-'),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Seksi')
                    ->trueLabel('Seksi Aktif')
                    ->falseLabel('Seksi Tidak Aktif')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->toolbarActions([])
            ->defaultSort('name')
            ->paginationPageOptions([10,25,50])
            ->emptyStateHeading('Belum ada data seksi')
            ->emptyStateDescription('Data seksi Kantor Camat akan tampil di halaman ini.')
            ->emptyStateIcon('heroicon-o-building-office-2');
    }
}