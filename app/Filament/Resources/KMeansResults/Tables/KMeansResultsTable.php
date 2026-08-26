<?php

namespace App\Filament\Resources\KMeansResults\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KMeansResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('kmeans_run_id')
                    ->label('Run')
                    ->formatStateUsing(
                        fn ($state): string => '#'.$state
                    ),

                TextColumn::make('dataset_name')
                    ->label('Dataset')
                    ->badge()
                    ->searchable(),

                TextColumn::make('month')
                    ->label('Bulan')
                    ->formatStateUsing(
                        fn ($state): string => match ((int) $state) {
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                            default => (string) $state,
                        }
                    ),

                TextColumn::make('year')
                    ->label('Tahun'),

                TextColumn::make('jumlah_pelayanan')
                    ->label('Jumlah Pelayanan')
                    ->numeric(),

                TextColumn::make('hari_aktif')
                    ->label('Hari Aktif')
                    ->numeric(),

                TextColumn::make('rata_rata_harian')
                    ->label('Rata-rata/Hari')
                    ->numeric(decimalPlaces: 2),

                TextColumn::make('cluster')
                    ->label('Cluster')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state): string => 'C'.$state
                    )
                    ->color(
                        fn ($state): string => match ((int) $state) {
                            1 => 'gray',
                            2 => 'warning',
                            3 => 'success',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('cluster_label')
                    ->label('Kategori')
                    ->badge()
                    ->color(
                        fn ($state): string => match ($state) {
                            'Rendah' => 'gray',
                            'Sedang' => 'warning',
                            'Tinggi' => 'success',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('reference_label')
                    ->label('Label Referensi')
                    ->badge()
                    ->placeholder('Belum divalidasi')
                    ->color(
                        fn ($state): string => match ($state) {
                            'Rendah' => 'gray',
                            'Sedang' => 'warning',
                            'Tinggi' => 'success',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('distance_to_centroid')
                    ->label('Jarak Centroid')
                    ->numeric(decimalPlaces: 6),

                TextColumn::make('z_jumlah_pelayanan')
                    ->label('Z Jumlah')
                    ->numeric(decimalPlaces: 6)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('z_hari_aktif')
                    ->label('Z Hari')
                    ->numeric(decimalPlaces: 6)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('dataset_name')
                    ->label('Dataset')
                    ->options([
                        'Pewarisan' => 'Pewarisan',
                        'Izin Meneliti' => 'Izin Meneliti',
                        'Rekomendasi Kegiatan' => 'Rekomendasi Kegiatan',
                    ]),

                SelectFilter::make('cluster')
                    ->label('Cluster')
                    ->options([
                        1 => 'C1 - Rendah',
                        2 => 'C2 - Sedang',
                        3 => 'C3 - Tinggi',
                    ]),

                SelectFilter::make('reference_label')
                    ->label('Label Referensi')
                    ->options([
                        'Rendah' => 'Rendah',
                        'Sedang' => 'Sedang',
                        'Tinggi' => 'Tinggi',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),

                EditAction::make()
                    ->label('Validasi')
                    ->icon('heroicon-o-check-badge'),
            ])
            ->toolbarActions([])
            ->paginated([
                10,
                25,
                50,
            ])
            ->defaultPaginationPageOption(25);
    }
}
