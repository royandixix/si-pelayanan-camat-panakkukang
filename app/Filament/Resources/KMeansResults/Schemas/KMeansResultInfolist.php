<?php

namespace App\Filament\Resources\KMeansResults\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KMeansResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hasil Clustering')
                    ->schema([
                        TextEntry::make('kmeans_run_id')
                            ->label('Run')
                            ->formatStateUsing(
                                fn ($state): string => '#'.$state
                            ),

                        TextEntry::make('dataset_name')
                            ->label('Dataset')
                            ->badge(),

                        TextEntry::make('month')
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

                        TextEntry::make('year')
                            ->label('Tahun'),

                        TextEntry::make('jumlah_pelayanan')
                            ->label('Jumlah Pelayanan')
                            ->numeric(),

                        TextEntry::make('hari_aktif')
                            ->label('Hari Aktif')
                            ->numeric(),

                        TextEntry::make('rata_rata_harian')
                            ->label('Rata-rata Pelayanan/Hari')
                            ->numeric(decimalPlaces: 4),

                        TextEntry::make('z_jumlah_pelayanan')
                            ->label('Z-Score Jumlah Pelayanan')
                            ->numeric(decimalPlaces: 8),

                        TextEntry::make('z_hari_aktif')
                            ->label('Z-Score Hari Aktif')
                            ->numeric(decimalPlaces: 8),

                        TextEntry::make('cluster')
                            ->label('Cluster')
                            ->badge()
                            ->formatStateUsing(
                                fn ($state): string => 'C'.$state
                            ),

                        TextEntry::make('cluster_label')
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

                        TextEntry::make('distance_to_centroid')
                            ->label('Jarak ke Centroid')
                            ->numeric(decimalPlaces: 8),

                        TextEntry::make('reference_label')
                            ->label('Label Referensi')
                            ->placeholder('Belum tersedia'),
                    ])
                    ->columns(2),
            ]);
    }
}
