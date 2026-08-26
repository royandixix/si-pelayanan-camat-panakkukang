<?php

namespace App\Filament\Resources\BlackBoxTests\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BlackBoxTestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable(),

                TextColumn::make('module')
                    ->label('Modul')
                    ->badge()
                    ->searchable(),

                TextColumn::make('scenario')
                    ->label('Skenario')
                    ->wrap(),

                TextColumn::make('expected_result')
                    ->label('Hasil Diharapkan')
                    ->wrap(),

                TextColumn::make('actual_result')
                    ->label('Hasil Aktual')
                    ->placeholder('Belum diuji')
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string => match ($state) {
                            'belum_diuji' => 'Belum Diuji',
                            'lulus' => 'Lulus',
                            'gagal' => 'Gagal',
                            default => (string) $state,
                        }
                    )
                    ->color(
                        fn ($state): string => match ($state) {
                            'lulus' => 'success',
                            'gagal' => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('tested_at')
                    ->label('Waktu Uji')
                    ->dateTime('d-m-Y H:i')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'belum_diuji' => 'Belum Diuji',
                        'lulus' => 'Lulus',
                        'gagal' => 'Gagal',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Isi Hasil'),
            ])
            ->toolbarActions([]);
    }
}
