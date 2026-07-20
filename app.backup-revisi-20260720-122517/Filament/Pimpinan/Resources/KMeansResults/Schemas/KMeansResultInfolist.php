<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class KMeansResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pelaksanaan K-Means')
                    ->description(
                        'Informasi periode, proses, dan kualitas hasil klasterisasi.',
                    )
                    ->icon('heroicon-o-cog-6-tooth')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('run.period_start')
                            ->label('Periode Mulai')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('run.period_end')
                            ->label('Periode Selesai')
                            ->date('d M Y')
                            ->placeholder('-'),

                        TextEntry::make('run.status')
                            ->label('Status Analisis')
                            ->badge()
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatStatus($state),
                            )
                            ->color(
                                fn (mixed $state): string =>
                                    self::statusColor($state),
                            ),

                        TextEntry::make('run.cluster_count')
                            ->label('Jumlah Cluster')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('run.iterations')
                            ->label('Jumlah Iterasi')
                            ->numeric()
                            ->placeholder('-'),

                        TextEntry::make('run.wcss')
                            ->label('Nilai WCSS')
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatDecimal($state),
                            )
                            ->placeholder('-'),

                        TextEntry::make('run.silhouette_score')
                            ->label('Silhouette Score')
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatDecimal($state),
                            )
                            ->placeholder('-'),

                        TextEntry::make('run.davies_bouldin_index')
                            ->label('Davies-Bouldin Index')
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatDecimal($state),
                            )
                            ->placeholder('-'),

                        TextEntry::make('run.executor.name')
                            ->label('Dijalankan Oleh')
                            ->placeholder('Sistem'),

                        TextEntry::make('run.executed_at')
                            ->label('Waktu Pelaksanaan')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('run.notes')
                            ->label('Catatan Analisis')
                            ->placeholder('Tidak ada catatan.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Hasil Analisis Seksi')
                    ->description(
                        'Hasil pengelompokan beban kerja dan rekomendasi distribusi pegawai.',
                    )
                    ->icon('heroicon-o-chart-bar')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('section.code')
                            ->label('Kode Seksi')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('section.name')
                            ->label('Nama Seksi')
                            ->weight('bold')
                            ->columnSpan(2),

                        TextEntry::make('service_volume')
                            ->label('Volume Permohonan')
                            ->numeric(),

                        TextEntry::make('queue_volume')
                            ->label('Volume Antrean')
                            ->numeric(),

                        TextEntry::make('total_volume')
                            ->label('Total Volume')
                            ->numeric()
                            ->weight('bold')
                            ->color('primary'),

                        TextEntry::make('employee_count')
                            ->label('Jumlah Pegawai')
                            ->numeric(),

                        TextEntry::make('cluster_number')
                            ->label('Nomor Cluster')
                            ->formatStateUsing(
                                fn (mixed $state): string => 'C' . $state,
                            )
                            ->badge()
                            ->color('info'),

                        TextEntry::make('rank')
                            ->label('Peringkat Beban')
                            ->numeric()
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('centroid')
                            ->label('Nilai Centroid')
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatDecimal($state),
                            ),

                        TextEntry::make('distance_to_centroid')
                            ->label('Jarak ke Centroid')
                            ->formatStateUsing(
                                fn (mixed $state): string =>
                                    self::formatDecimal($state),
                            ),

                        TextEntry::make('workload_category')
                            ->label('Kategori Beban Kerja')
                            ->badge()
                            ->formatStateUsing(
                                fn (?string $state): string =>
                                    self::workloadLabel($state),
                            )
                            ->color(
                                fn (?string $state): string =>
                                    self::workloadColor($state),
                            ),

                        TextEntry::make('recommended_employee_change')
                            ->label('Perubahan Pegawai')
                            ->formatStateUsing(function (mixed $state): string {
                                $value = (int) $state;

                                if ($value > 0) {
                                    return '+' . $value . ' pegawai';
                                }

                                if ($value < 0) {
                                    return $value . ' pegawai';
                                }

                                return 'Tidak berubah';
                            })
                            ->badge()
                            ->color(function (mixed $state): string {
                                $value = (int) $state;

                                return match (true) {
                                    $value > 0 => 'success',
                                    $value < 0 => 'warning',
                                    default => 'gray',
                                };
                            }),

                        TextEntry::make('recommendation')
                            ->label('Rekomendasi Keputusan')
                            ->placeholder('Belum ada rekomendasi.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function formatStatus(mixed $state): string
    {
        if ($state instanceof \BackedEnum) {
            $state = $state->value;
        }

        return Str::headline((string) $state);
    }

    private static function statusColor(mixed $state): string
    {
        if ($state instanceof \BackedEnum) {
            $state = $state->value;
        }

        return match (Str::lower((string) $state)) {
            'completed', 'selesai', 'success' => 'success',
            'processing', 'running', 'diproses' => 'info',
            'failed', 'gagal' => 'danger',
            'pending', 'menunggu' => 'warning',
            default => 'gray',
        };
    }

    private static function formatDecimal(mixed $state): string
    {
        if ($state === null || $state === '') {
            return '-';
        }

        return number_format((float) $state, 6, ',', '.');
    }

    private static function workloadLabel(?string $state): string
    {
        $value = Str::lower(trim((string) $state));

        return match (true) {
            Str::contains($value, ['high', 'tinggi']) => 'Beban Tinggi',
            Str::contains($value, ['medium', 'sedang']) => 'Beban Sedang',
            Str::contains($value, ['low', 'rendah']) => 'Beban Rendah',
            blank($value) => '-',
            default => Str::headline($value),
        };
    }

    private static function workloadColor(?string $state): string
    {
        $value = Str::lower(trim((string) $state));

        return match (true) {
            Str::contains($value, ['high', 'tinggi']) => 'danger',
            Str::contains($value, ['medium', 'sedang']) => 'warning',
            Str::contains($value, ['low', 'rendah']) => 'success',
            default => 'gray',
        };
    }
}
