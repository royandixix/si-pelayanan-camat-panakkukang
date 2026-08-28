<?php

namespace App\Services;

use App\Models\KMeansResult;
use App\Models\KMeansRun;
use App\Models\ResearchDatasetRecord;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class KMeansService
{
    public function run(int $k = 3): KMeansRun
    {
        if ($k !== 3) {
            throw new RuntimeException('Penelitian ini menggunakan K = 3.');
        }

        if (! $this->hasDatasetChanged()) {
            throw new RuntimeException(
                'Data sumber K-Means belum berubah. Proses ulang tidak diperlukan.'
            );
        }

        $totalSourceRecords = ResearchDatasetRecord::query()->count();

        $validSourceRecords = ResearchDatasetRecord::query()
            ->where('validation_status', 'valid')
            ->count();

        $excludedRecords = $totalSourceRecords - $validSourceRecords;

        $points = $this->buildPoints();

        if (count($points) < $k) {
            throw new RuntimeException('Jumlah titik data tidak mencukupi untuk proses K-Means.');
        }

        [$normalized, $normalizationStats] = $this->normalize($points);

        [$assignments, $centroids, $iterations] = $this->cluster(
            $normalized,
            $k
        );

        [
            $assignments,
            $centroidVectors,
            $centroidSummary,
        ] = $this->relabelClusters(
            $points,
            $assignments,
            $centroids
        );

        $wcss = $this->calculateWcss(
            $normalized,
            $assignments,
            $centroidVectors
        );

        $silhouette = $this->calculateSilhouette(
            $normalized,
            $assignments
        );

        return DB::transaction(function () use (
            $k,
            $totalSourceRecords,
            $validSourceRecords,
            $excludedRecords,
            $points,
            $normalized,
            $normalizationStats,
            $assignments,
            $centroidVectors,
            $centroidSummary,
            $iterations,
            $wcss,
            $silhouette
        ) {
            $run = KMeansRun::query()->create([
                'k' => $k,
                'total_source_records' => $totalSourceRecords,
                'valid_source_records' => $validSourceRecords,
                'excluded_records' => $excludedRecords,
                'total_points' => count($points),
                'features' => [
                    'jumlah_pelayanan',
                    'hari_aktif',
                ],
                'normalization' => 'z_score',
                'iterations' => $iterations,
                'wcss' => round($wcss, 8),
                'silhouette_score' => round($silhouette, 6),
                'cluster_centroids' => [
                    'normalization_stats' => $normalizationStats,
                    'clusters' => $centroidSummary,
                ],
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            foreach ($points as $index => $point) {
                $cluster = $assignments[$index];

                $distance = $this->distance(
                    $normalized[$index],
                    $centroidVectors[$cluster]
                );

                KMeansResult::query()->create([
                    'kmeans_run_id' => $run->id,
                    'dataset_name' => $point['dataset_name'],
                    'year' => $point['year'],
                    'month' => $point['month'],
                    'jumlah_pelayanan' => $point['jumlah_pelayanan'],
                    'hari_aktif' => $point['hari_aktif'],
                    'rata_rata_harian' => $point['rata_rata_harian'],
                    'z_jumlah_pelayanan' => round($normalized[$index][0], 8),
                    'z_hari_aktif' => round($normalized[$index][1], 8),
                    'cluster' => $cluster,
                    'cluster_label' => match ($cluster) {
                        1 => 'Rendah',
                        2 => 'Sedang',
                        3 => 'Tinggi',
                    },
                    'distance_to_centroid' => round($distance, 8),
                    'reference_label' => null,
                ]);
            }

            return $run->fresh('results');
        });
    }

    public function hasDatasetChanged(): bool
    {
        $latestRun = KMeansRun::query()
            ->where('status', 'completed')
            ->latest('id')
            ->first();

        if (! $latestRun) {
            return true;
        }

        $currentPoints = $this->buildPoints();

        $previousPoints = KMeansResult::query()
            ->where('kmeans_run_id', $latestRun->id)
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('dataset_name')
            ->get([
                'dataset_name',
                'year',
                'month',
                'jumlah_pelayanan',
                'hari_aktif',
            ])
            ->map(
                fn (KMeansResult $result): array => [
                    'dataset_name' => $result->dataset_name,
                    'year' => $result->year,
                    'month' => $result->month,
                    'jumlah_pelayanan' => $result->jumlah_pelayanan,
                    'hari_aktif' => $result->hari_aktif,
                ]
            )
            ->values()
            ->all();

        return $this->makePointsSignature($currentPoints)
            !== $this->makePointsSignature($previousPoints);
    }

    private function makePointsSignature(array $points): string
    {
        $canonical = array_map(
            fn (array $point): array => [
                'dataset_name' => (string) $point['dataset_name'],
                'year' => (int) $point['year'],
                'month' => (int) $point['month'],
                'jumlah_pelayanan' => (int) $point['jumlah_pelayanan'],
                'hari_aktif' => (int) $point['hari_aktif'],
            ],
            $points
        );

        return hash(
            'sha256',
            (string) json_encode(
                $canonical,
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            )
        );
    }

    private function buildPoints(): array
    {
        return ResearchDatasetRecord::query()
            ->selectRaw(
                'dataset_name,
                YEAR(record_date) as tahun,
                MONTH(record_date) as bulan,
                COUNT(*) as jumlah_pelayanan,
                COUNT(DISTINCT record_date) as hari_aktif'
            )
            ->where('validation_status', 'valid')
            ->whereNotNull('record_date')
            ->groupBy('dataset_name')
            ->groupByRaw('YEAR(record_date), MONTH(record_date)')
            ->orderByRaw('YEAR(record_date), MONTH(record_date), dataset_name')
            ->get()
            ->map(function ($row): array {
                $jumlah = (int) $row->jumlah_pelayanan;
                $hariAktif = (int) $row->hari_aktif;

                return [
                    'dataset_name' => $row->dataset_name,
                    'year' => (int) $row->tahun,
                    'month' => (int) $row->bulan,
                    'jumlah_pelayanan' => $jumlah,
                    'hari_aktif' => $hariAktif,
                    'rata_rata_harian' => round(
                        $jumlah / max(1, $hariAktif),
                        4
                    ),
                ];
            })
            ->values()
            ->all();
    }

    private function normalize(array $points): array
    {
        $jumlahValues = array_column($points, 'jumlah_pelayanan');
        $hariValues = array_column($points, 'hari_aktif');

        $jumlahMean = array_sum($jumlahValues) / count($jumlahValues);
        $hariMean = array_sum($hariValues) / count($hariValues);

        $jumlahVariance = array_sum(
            array_map(
                fn ($value) => ($value - $jumlahMean) ** 2,
                $jumlahValues
            )
        ) / count($jumlahValues);

        $hariVariance = array_sum(
            array_map(
                fn ($value) => ($value - $hariMean) ** 2,
                $hariValues
            )
        ) / count($hariValues);

        $jumlahStd = sqrt($jumlahVariance);
        $hariStd = sqrt($hariVariance);

        if ($jumlahStd == 0.0) {
            $jumlahStd = 1.0;
        }

        if ($hariStd == 0.0) {
            $hariStd = 1.0;
        }

        $normalized = [];

        foreach ($points as $point) {
            $normalized[] = [
                ($point['jumlah_pelayanan'] - $jumlahMean) / $jumlahStd,
                ($point['hari_aktif'] - $hariMean) / $hariStd,
            ];
        }

        return [
            $normalized,
            [
                'jumlah_pelayanan' => [
                    'mean' => round($jumlahMean, 8),
                    'std' => round($jumlahStd, 8),
                ],
                'hari_aktif' => [
                    'mean' => round($hariMean, 8),
                    'std' => round($hariStd, 8),
                ],
            ],
        ];
    }

    private function cluster(array $points, int $k): array
    {
        $ordered = $points;

        usort(
            $ordered,
            fn ($a, $b) => ($a[0] + $a[1]) <=> ($b[0] + $b[1])
        );

        $positions = [
            0,
            intdiv(count($ordered) - 1, 2),
            count($ordered) - 1,
        ];

        $centroids = [];

        foreach ($positions as $index => $position) {
            $centroids[$index + 1] = $ordered[$position];
        }

        $previousAssignments = null;
        $assignments = [];
        $iterations = 0;

        for ($iteration = 1; $iteration <= 100; $iteration++) {
            $assignments = $this->assignPoints(
                $points,
                $centroids
            );

            $newCentroids = [];

            for ($cluster = 1; $cluster <= $k; $cluster++) {
                $members = [];

                foreach ($points as $index => $point) {
                    if ($assignments[$index] === $cluster) {
                        $members[] = $point;
                    }
                }

                if ($members === []) {
                    $newCentroids[$cluster] = $centroids[$cluster];
                    continue;
                }

                $newCentroids[$cluster] = [
                    array_sum(array_column($members, 0)) / count($members),
                    array_sum(array_column($members, 1)) / count($members),
                ];
            }

            $movement = 0.0;

            for ($cluster = 1; $cluster <= $k; $cluster++) {
                $movement = max(
                    $movement,
                    $this->distance(
                        $centroids[$cluster],
                        $newCentroids[$cluster]
                    )
                );
            }

            $sameAssignments = $previousAssignments !== null
                && $previousAssignments === $assignments;

            $centroids = $newCentroids;
            $previousAssignments = $assignments;
            $iterations = $iteration;

            if ($sameAssignments || $movement < 0.00000001) {
                break;
            }
        }

        $assignments = $this->assignPoints(
            $points,
            $centroids
        );

        return [
            $assignments,
            $centroids,
            $iterations,
        ];
    }

    private function assignPoints(array $points, array $centroids): array
    {
        $assignments = [];

        foreach ($points as $index => $point) {
            $selectedCluster = null;
            $selectedDistance = INF;

            foreach ($centroids as $cluster => $centroid) {
                $distance = $this->distance(
                    $point,
                    $centroid
                );

                if ($distance < $selectedDistance) {
                    $selectedDistance = $distance;
                    $selectedCluster = $cluster;
                }
            }

            $assignments[$index] = $selectedCluster;
        }

        return $assignments;
    }

    private function relabelClusters(
        array $rawPoints,
        array $assignments,
        array $centroids
    ): array {
        $scores = [];

        foreach ($centroids as $cluster => $centroid) {
            $scores[$cluster] = $centroid[0] + $centroid[1];
        }

        asort($scores, SORT_NUMERIC);

        $mapping = [];
        $position = 1;

        foreach (array_keys($scores) as $oldCluster) {
            $mapping[$oldCluster] = $position;
            $position++;
        }

        $newAssignments = [];

        foreach ($assignments as $index => $oldCluster) {
            $newAssignments[$index] = $mapping[$oldCluster];
        }

        $newCentroidVectors = [];
        $summary = [];

        foreach ($mapping as $oldCluster => $newCluster) {
            $newCentroidVectors[$newCluster] = $centroids[$oldCluster];

            $memberIndexes = [];

            foreach ($assignments as $index => $assignedCluster) {
                if ($assignedCluster === $oldCluster) {
                    $memberIndexes[] = $index;
                }
            }

            $jumlahValues = [];
            $hariValues = [];
            $rataValues = [];

            foreach ($memberIndexes as $index) {
                $jumlahValues[] = $rawPoints[$index]['jumlah_pelayanan'];
                $hariValues[] = $rawPoints[$index]['hari_aktif'];
                $rataValues[] = $rawPoints[$index]['rata_rata_harian'];
            }

            $count = count($memberIndexes);

            $summary[] = [
                'cluster' => $newCluster,
                'label' => match ($newCluster) {
                    1 => 'Rendah',
                    2 => 'Sedang',
                    3 => 'Tinggi',
                },
                'jumlah_titik' => $count,
                'centroid_normalized' => [
                    'jumlah_pelayanan' => round(
                        $centroids[$oldCluster][0],
                        8
                    ),
                    'hari_aktif' => round(
                        $centroids[$oldCluster][1],
                        8
                    ),
                ],
                'centroid_asli' => [
                    'jumlah_pelayanan' => round(
                        array_sum($jumlahValues) / $count,
                        4
                    ),
                    'hari_aktif' => round(
                        array_sum($hariValues) / $count,
                        4
                    ),
                    'rata_rata_harian' => round(
                        array_sum($rataValues) / $count,
                        4
                    ),
                ],
            ];
        }

        ksort($newCentroidVectors);

        usort(
            $summary,
            fn ($a, $b) => $a['cluster'] <=> $b['cluster']
        );

        return [
            $newAssignments,
            $newCentroidVectors,
            $summary,
        ];
    }

    private function calculateWcss(
        array $points,
        array $assignments,
        array $centroids
    ): float {
        $wcss = 0.0;

        foreach ($points as $index => $point) {
            $cluster = $assignments[$index];

            $distance = $this->distance(
                $point,
                $centroids[$cluster]
            );

            $wcss += $distance ** 2;
        }

        return $wcss;
    }

    private function calculateSilhouette(
        array $points,
        array $assignments
    ): float {
        $scores = [];
        $clusters = array_values(array_unique($assignments));

        foreach ($points as $index => $point) {
            $sameClusterIndexes = [];

            foreach ($assignments as $otherIndex => $cluster) {
                if (
                    $otherIndex !== $index
                    && $cluster === $assignments[$index]
                ) {
                    $sameClusterIndexes[] = $otherIndex;
                }
            }

            if ($sameClusterIndexes === []) {
                $scores[] = 0.0;
                continue;
            }

            $a = 0.0;

            foreach ($sameClusterIndexes as $otherIndex) {
                $a += $this->distance(
                    $point,
                    $points[$otherIndex]
                );
            }

            $a /= count($sameClusterIndexes);

            $otherClusterAverages = [];

            foreach ($clusters as $cluster) {
                if ($cluster === $assignments[$index]) {
                    continue;
                }

                $indexes = [];

                foreach ($assignments as $otherIndex => $assignedCluster) {
                    if ($assignedCluster === $cluster) {
                        $indexes[] = $otherIndex;
                    }
                }

                if ($indexes === []) {
                    continue;
                }

                $distanceSum = 0.0;

                foreach ($indexes as $otherIndex) {
                    $distanceSum += $this->distance(
                        $point,
                        $points[$otherIndex]
                    );
                }

                $otherClusterAverages[] =
                    $distanceSum / count($indexes);
            }

            if ($otherClusterAverages === []) {
                $scores[] = 0.0;
                continue;
            }

            $b = min($otherClusterAverages);
            $divisor = max($a, $b);

            $scores[] = $divisor == 0.0
                ? 0.0
                : ($b - $a) / $divisor;
        }

        return array_sum($scores) / count($scores);
    }

    private function distance(array $pointA, array $pointB): float
    {
        return sqrt(
            (($pointA[0] - $pointB[0]) ** 2)
            + (($pointA[1] - $pointB[1]) ** 2)
        );
    }
}
