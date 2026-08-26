<?php

namespace App\Filament\Resources\KMeansRuns\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KMeansRunInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Proses')
                    ->schema([
                        TextEntry::make('id')
                            ->label('Run')
                            ->formatStateUsing(
                                fn ($state): string => '#'.$state
                            ),

                        TextEntry::make('k')
                            ->label('Jumlah Cluster'),

                        TextEntry::make('total_source_records')
                            ->label('Data Sumber')
                            ->numeric(),

                        TextEntry::make('valid_source_records')
                            ->label('Data Valid')
                            ->numeric(),

                        TextEntry::make('excluded_records')
                            ->label('Data Dikeluarkan')
                            ->numeric(),

                        TextEntry::make('total_points')
                            ->label('Titik K-Means')
                            ->numeric(),

                        TextEntry::make('features')
                            ->label('Fitur')
                            ->state(
                                fn ($record): string => implode(
                                    ', ',
                                    $record->features ?? []
                                )
                            ),

                        TextEntry::make('normalization')
                            ->label('Normalisasi')
                            ->formatStateUsing(
                                fn ($state): string => $state === 'z_score'
                                    ? 'Z-Score'
                                    : (string) $state
                            ),

                        TextEntry::make('iterations')
                            ->label('Jumlah Iterasi')
                            ->numeric(),

                        TextEntry::make('wcss')
                            ->label('WCSS')
                            ->numeric(decimalPlaces: 8),

                        TextEntry::make('silhouette_score')
                            ->label('Silhouette Score')
                            ->numeric(decimalPlaces: 6),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(
                                fn ($state): string => match ($state) {
                                    'completed' => 'Selesai',
                                    'processing' => 'Diproses',
                                    'failed' => 'Gagal',
                                    default => ucfirst((string) $state),
                                }
                            )
                            ->color(
                                fn ($state): string => match ($state) {
                                    'completed' => 'success',
                                    'processing' => 'warning',
                                    'failed' => 'danger',
                                    default => 'gray',
                                }
                            ),

                        TextEntry::make('processed_at')
                            ->label('Waktu Proses')
                            ->dateTime('d-m-Y H:i'),
                    ])
                    ->columns(3),

                Section::make('Centroid Akhir')
                    ->schema([
                        TextEntry::make('centroid_c1')
                            ->label('C1 - Rendah')
                            ->state(function ($record): string {
                                $cluster = collect(
                                    data_get(
                                        $record->cluster_centroids,
                                        'clusters',
                                        []
                                    )
                                )->firstWhere('cluster', 1);

                                if (! $cluster) {
                                    return '-';
                                }

                                return 'Jumlah pelayanan: '
                                    .data_get($cluster, 'centroid_asli.jumlah_pelayanan', '-')
                                    .' | Hari aktif: '
                                    .data_get($cluster, 'centroid_asli.hari_aktif', '-')
                                    .' | Titik: '
                                    .data_get($cluster, 'jumlah_titik', '-');
                            }),

                        TextEntry::make('centroid_c2')
                            ->label('C2 - Sedang')
                            ->state(function ($record): string {
                                $cluster = collect(
                                    data_get(
                                        $record->cluster_centroids,
                                        'clusters',
                                        []
                                    )
                                )->firstWhere('cluster', 2);

                                if (! $cluster) {
                                    return '-';
                                }

                                return 'Jumlah pelayanan: '
                                    .data_get($cluster, 'centroid_asli.jumlah_pelayanan', '-')
                                    .' | Hari aktif: '
                                    .data_get($cluster, 'centroid_asli.hari_aktif', '-')
                                    .' | Titik: '
                                    .data_get($cluster, 'jumlah_titik', '-');
                            }),

                        TextEntry::make('centroid_c3')
                            ->label('C3 - Tinggi')
                            ->state(function ($record): string {
                                $cluster = collect(
                                    data_get(
                                        $record->cluster_centroids,
                                        'clusters',
                                        []
                                    )
                                )->firstWhere('cluster', 3);

                                if (! $cluster) {
                                    return '-';
                                }

                                return 'Jumlah pelayanan: '
                                    .data_get($cluster, 'centroid_asli.jumlah_pelayanan', '-')
                                    .' | Hari aktif: '
                                    .data_get($cluster, 'centroid_asli.hari_aktif', '-')
                                    .' | Titik: '
                                    .data_get($cluster, 'jumlah_titik', '-');
                            }),
                    ])
                    ->columns(1),
            ]);
    }
}
