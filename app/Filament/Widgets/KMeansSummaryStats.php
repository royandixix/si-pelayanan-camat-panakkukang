<?php

namespace App\Filament\Widgets;

use App\Models\KMeansRun;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KMeansSummaryStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $run = KMeansRun::query()
            ->latest('id')
            ->first();

        if (! $run) {
            return [
                Stat::make('Run K-Means', '-'),
                Stat::make('C1 - Rendah', 0),
                Stat::make('C2 - Sedang', 0),
                Stat::make('C3 - Tinggi', 0),
            ];
        }

        $c1 = $run->results()
            ->where('cluster', 1)
            ->count();

        $c2 = $run->results()
            ->where('cluster', 2)
            ->count();

        $c3 = $run->results()
            ->where('cluster', 3)
            ->count();

        return [
            Stat::make(
                'Run K-Means',
                '#'.$run->id
            )
                ->description(
                    $run->total_points.' titik data'
                ),

            Stat::make(
                'C1 - Rendah',
                $c1
            )
                ->description('Aktivitas rendah'),

            Stat::make(
                'C2 - Sedang',
                $c2
            )
                ->description('Aktivitas sedang'),

            Stat::make(
                'C3 - Tinggi',
                $c3
            )
                ->description('Aktivitas tinggi'),

            Stat::make(
                'WCSS',
                number_format($run->wcss, 8)
            )
                ->description(
                    $run->iterations.' iterasi'
                ),

            Stat::make(
                'Silhouette Score',
                number_format(
                    $run->silhouette_score,
                    6
                )
            )
                ->description('Evaluasi internal clustering'),
        ];
    }
}
