<?php

namespace App\Livewire\Admin;

use App\Models\KMeansResult;
use App\Models\KMeansRun;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class KMeansTrueFalseEvaluation extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getResultsQuery())
            ->columns([
                TextColumn::make('dataset_name')
                    ->label('Dataset')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('year')
                    ->label('Tahun')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('month')
                    ->label('Bulan')
                    ->alignCenter()
                    ->formatStateUsing(
                        fn ($state): string =>
                            str_pad((string) $state, 2, '0', STR_PAD_LEFT)
                    ),

                TextColumn::make('jumlah_pelayanan')
                    ->label('Jumlah Pelayanan')
                    ->alignCenter()
                    ->numeric(),

                TextColumn::make('hari_aktif')
                    ->label('Hari Aktif')
                    ->alignCenter()
                    ->numeric(),

                TextColumn::make('cluster_label')
                    ->label('Hasil K-Means')
                    ->badge()
                    ->alignCenter()
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
                    ->alignCenter()
                    ->placeholder('Belum Diisi')
                    ->color(
                        fn ($state): string => match ($state) {
                            'Rendah' => 'gray',
                            'Sedang' => 'warning',
                            'Tinggi' => 'success',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('hasil_evaluasi')
                    ->label('Hasil')
                    ->alignCenter()
                    ->state(function (KMeansResult $record): string {
                        if (! in_array(
                            $record->reference_label,
                            ['Rendah', 'Sedang', 'Tinggi'],
                            true
                        )) {
                            return 'Menunggu';
                        }

                        return $record->reference_label === $record->cluster_label
                            ? 'Benar'
                            : 'Salah';
                    })
                    ->badge()
                    ->color(
                        fn ($state): string => match ($state) {
                            'Benar' => 'success',
                            'Salah' => 'danger',
                            default => 'gray',
                        }
                    ),
            ])
            ->recordActions([
                Action::make('isiLabel')
                    ->label('Isi Label')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(
                        fn (KMeansResult $record): string =>
                            url(
                                '/admin/hasil-clustering/'
                                .$record->id
                                .'/label-referensi'
                            )
                    )
                    ->visible(
                        fn (KMeansResult $record): bool =>
                            ! in_array(
                                $record->reference_label,
                                ['Rendah', 'Sedang', 'Tinggi'],
                                true
                            )
                    ),
            ])
            ->defaultSort('dataset_name')
            ->paginated([
                10,
                25,
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Belum ada hasil K-Means')
            ->emptyStateDescription(
                'Belum tersedia data hasil clustering untuk diuji.'
            )
            ->emptyStateIcon('heroicon-o-table-cells');
    }

    private function getResultsQuery(): Builder
    {
        $runId = KMeansRun::query()
            ->where('status', 'completed')
            ->latest('id')
            ->value('id');

        if (! $runId) {
            return KMeansResult::query()
                ->whereRaw('1 = 0');
        }

        return KMeansResult::query()
            ->where('kmeans_run_id', $runId);
    }

    public function render(): View
    {
        $run = KMeansRun::query()
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        $results = collect();

        if ($run) {
            $results = KMeansResult::query()
                ->where('kmeans_run_id', $run->id)
                ->get();
        }

        $labels = [
            'Rendah',
            'Sedang',
            'Tinggi',
        ];

        $validated = $results
            ->filter(
                fn (KMeansResult $row): bool =>
                    in_array(
                        $row->reference_label,
                        $labels,
                        true
                    )
            )
            ->values();

        $ready = $results->count() > 0
            && $validated->count() === $results->count();

        $metrics = [];

        foreach ($labels as $label) {
            $tp = $validated->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label === $label
                    && $row->cluster_label === $label
            )->count();

            $fn = $validated->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label === $label
                    && $row->cluster_label !== $label
            )->count();

            $fp = $validated->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label !== $label
                    && $row->cluster_label === $label
            )->count();

            $tn = $validated->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label !== $label
                    && $row->cluster_label !== $label
            )->count();

            $total = $tp + $tn + $fp + $fn;

            $accuracy = $ready && $total > 0
                ? (($tp + $tn) / $total) * 100
                : null;

            $precision = $ready && ($tp + $fp) > 0
                ? ($tp / ($tp + $fp)) * 100
                : null;

            $recall = $ready && ($tp + $fn) > 0
                ? ($tp / ($tp + $fn)) * 100
                : null;

            $f1 = (
                $ready
                && $precision !== null
                && $recall !== null
                && ($precision + $recall) > 0
            )
                ? 2 * ($precision * $recall) / ($precision + $recall)
                : null;

            $metrics[$label] = [
                'tp' => $tp,
                'tn' => $tn,
                'fp' => $fp,
                'fn' => $fn,
                'accuracy' => $accuracy,
                'precision' => $precision,
                'recall' => $recall,
                'f1' => $f1,
            ];
        }

        $correct = $validated
            ->filter(
                fn (KMeansResult $row): bool =>
                    $row->reference_label === $row->cluster_label
            )
            ->count();

        $overallAccuracy = $ready
            ? ($correct / max(1, $validated->count())) * 100
            : null;

        return view(
            'livewire.admin.k-means-true-false-evaluation',
            [
                'run' => $run,
                'results' => $results,
                'validated' => $validated,
                'labels' => $labels,
                'metrics' => $metrics,
                'ready' => $ready,
                'overallAccuracy' => $overallAccuracy,
            ]
        );
    }
}
