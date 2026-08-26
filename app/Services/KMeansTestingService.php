<?php

namespace App\Services;

use App\Models\KMeansResult;
use App\Models\KMeansRun;
use BackedEnum;

class KMeansTestingService
{
    public function evaluate(): array
    {
        $run = KMeansRun::query()
            ->latest('id')
            ->first();

        if (! $run) {
            return [
                'run_id' => null,
                'checks' => [],
                'clusters' => [],
                'distribution' => [],
            ];
        }

        $results = KMeansResult::query()
            ->where('kmeans_run_id', $run->id)
            ->get();

        $distribution = $results
            ->groupBy('cluster')
            ->map(fn ($items): int => $items->count())
            ->sortKeys()
            ->all();

        $clusters = collect(
            data_get($run->cluster_centroids, 'clusters', [])
        )
            ->map(function (array $cluster): array {
                return [
                    'cluster' => data_get($cluster, 'cluster'),
                    'label' => data_get($cluster, 'label'),
                    'jumlah_titik' => data_get($cluster, 'jumlah_titik'),
                    'z_jumlah' => data_get(
                        $cluster,
                        'centroid_normalized.jumlah_pelayanan'
                    ),
                    'z_hari' => data_get(
                        $cluster,
                        'centroid_normalized.hari_aktif'
                    ),
                    'jumlah_pelayanan' => data_get(
                        $cluster,
                        'centroid_asli.jumlah_pelayanan'
                    ),
                    'hari_aktif' => data_get(
                        $cluster,
                        'centroid_asli.hari_aktif'
                    ),
                    'rata_rata_harian' => data_get(
                        $cluster,
                        'centroid_asli.rata_rata_harian'
                    ),
                ];
            })
            ->values()
            ->all();

        $status = $run->status instanceof BackedEnum
            ? $run->status->value
            : (string) $run->status;

        $distributionTotal = array_sum($distribution);

        $checks = [
            [
                'name' => 'Status proses K-Means',
                'expected' => 'completed',
                'actual' => $status,
                'passed' => $status === 'completed',
            ],
            [
                'name' => 'Jumlah hasil clustering',
                'expected' => $run->total_points,
                'actual' => $results->count(),
                'passed' => $results->count() === (int) $run->total_points,
            ],
            [
                'name' => 'Total anggota seluruh cluster',
                'expected' => $run->total_points,
                'actual' => $distributionTotal,
                'passed' => $distributionTotal === (int) $run->total_points,
            ],
            [
                'name' => 'Jumlah cluster terbentuk',
                'expected' => $run->k,
                'actual' => count($distribution),
                'passed' => count($distribution) === (int) $run->k,
            ],
            [
                'name' => 'Nilai WCSS',
                'expected' => '>= 0',
                'actual' => $run->wcss,
                'passed' => $run->wcss !== null
                    && (float) $run->wcss >= 0,
            ],
            [
                'name' => 'Rentang Silhouette Score',
                'expected' => '-1 sampai 1',
                'actual' => $run->silhouette_score,
                'passed' => $run->silhouette_score !== null
                    && (float) $run->silhouette_score >= -1
                    && (float) $run->silhouette_score <= 1,
            ],
        ];

        return [
            'run_id' => $run->id,
            'k' => $run->k,
            'total_source_records' => $run->total_source_records,
            'valid_source_records' => $run->valid_source_records,
            'excluded_records' => $run->excluded_records,
            'total_points' => $run->total_points,
            'result_count' => $results->count(),
            'features' => $run->features ?? [],
            'normalization' => $run->normalization,
            'iterations' => $run->iterations,
            'wcss' => $run->wcss,
            'silhouette_score' => $run->silhouette_score,
            'status' => $status,
            'processed_at' => $run->processed_at,
            'normalization_stats' => data_get(
                $run->cluster_centroids,
                'normalization_stats',
                []
            ),
            'clusters' => $clusters,
            'distribution' => $distribution,
            'checks' => $checks,
            'passed_checks' => collect($checks)
                ->where('passed', true)
                ->count(),
            'total_checks' => count($checks),
        ];
    }
}
