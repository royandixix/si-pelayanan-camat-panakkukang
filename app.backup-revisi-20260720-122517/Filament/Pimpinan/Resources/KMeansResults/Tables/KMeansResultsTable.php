<?php

namespace App\Filament\Pimpinan\Resources\KMeansResults\Tables;

use App\Models\KMeansResult;
use App\Models\KMeansRun;
use App\Models\Section;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class KMeansResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rank')
                    ->label('Peringkat')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('section.name')
                    ->label('Nama Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('semibold'),

                TextColumn::make('run.period_start')
                    ->label('Periode Mulai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('run.period_end')
                    ->label('Periode Selesai')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('service_volume')
                    ->label('Volume Layanan')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('queue_volume')
                    ->label('Volume Antrean')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('total_volume')
                    ->label('Total Volume')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('employee_count')
                    ->label('Jumlah Pegawai')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('cluster_number')
                    ->label('Cluster')
                    ->formatStateUsing(
                        fn (mixed $state): string => 'C' . $state,
                    )
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('centroid')
                    ->label('Centroid')
                    ->formatStateUsing(
                        fn (mixed $state): string =>
                            number_format((float) $state, 6, ',', '.'),
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('distance_to_centroid')
                    ->label('Jarak Centroid')
                    ->formatStateUsing(
                        fn (mixed $state): string =>
                            number_format((float) $state, 6, ',', '.'),
                    )
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('workload_category')
                    ->label('Kategori Beban')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            self::workloadLabel($state),
                    )
                    ->color(
                        fn (?string $state): string =>
                            self::workloadColor($state),
                    )
                    ->sortable(),

                TextColumn::make('recommended_employee_change')
                    ->label('Rekomendasi Pegawai')
                    ->formatStateUsing(function (mixed $state): string {
                        $value = (int) $state;

                        if ($value > 0) {
                            return '+' . $value . ' pegawai';
                        }

                        if ($value < 0) {
                            return $value . ' pegawai';
                        }

                        return 'Tetap';
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

                TextColumn::make('recommendation')
                    ->label('Rekomendasi Keputusan')
                    ->wrap()
                    ->limit(90)
                    ->tooltip(
                        fn (KMeansResult $record): ?string =>
                            $record->recommendation,
                    ),

                TextColumn::make('run.executed_at')
                    ->label('Waktu Analisis')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('k_means_run_id')
                    ->label('Periode Analisis')
                    ->options(function (): array {
                        return KMeansRun::query()
                            ->orderByDesc('executed_at')
                            ->orderByDesc('id')
                            ->get()
                            ->mapWithKeys(function (KMeansRun $run): array {
                                $start = $run->period_start?->format('d M Y') ?? '-';
                                $end = $run->period_end?->format('d M Y') ?? '-';

                                return [
                                    $run->id => $start . ' sampai ' . $end,
                                ];
                            })
                            ->all();
                    })
                    ->searchable()
                    ->preload(),

                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->options(
                        fn (): array => Section::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all(),
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('workload_category')
                    ->label('Kategori Beban')
                    ->options(function (): array {
                        return KMeansResult::query()
                            ->whereNotNull('workload_category')
                            ->select('workload_category')
                            ->distinct()
                            ->orderBy('workload_category')
                            ->pluck('workload_category')
                            ->mapWithKeys(
                                fn (string $category): array => [
                                    $category => self::workloadLabel($category),
                                ],
                            )
                            ->all();
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->toolbarActions([])
            ->defaultSort('rank')
            ->paginationPageOptions([5, 10, 25, 50])
            ->emptyStateHeading('Belum ada hasil K-Means')
            ->emptyStateDescription(
                'Hasil analisis akan tampil setelah proses K-Means dijalankan oleh Super Admin.',
            )
            ->emptyStateIcon('heroicon-o-chart-bar-square');
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
