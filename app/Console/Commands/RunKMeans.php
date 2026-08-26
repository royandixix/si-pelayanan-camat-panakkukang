<?php

namespace App\Console\Commands;

use App\Services\KMeansService;
use Illuminate\Console\Command;
use Throwable;

class RunKMeans extends Command
{
    protected $signature = 'kmeans:run';

    protected $description = 'Menjalankan proses K-Means dataset penelitian Kecamatan Panakkukang';

    public function handle(KMeansService $service): int
    {
        try {
            $run = $service->run(3);

            $this->newLine();
            $this->info('PROSES K-MEANS BERHASIL');
            $this->line(str_repeat('=', 55));
            $this->line('Run ID             : '.$run->id);
            $this->line('K                  : '.$run->k);
            $this->line('Data sumber        : '.$run->total_source_records);
            $this->line('Data valid         : '.$run->valid_source_records);
            $this->line('Data dikeluarkan   : '.$run->excluded_records);
            $this->line('Titik K-Means      : '.$run->total_points);
            $this->line('Jumlah iterasi      : '.$run->iterations);
            $this->line('WCSS                : '.number_format($run->wcss, 8));
            $this->line('Silhouette Score    : '.number_format($run->silhouette_score, 6));
            $this->newLine();

            $rows = $run->results
                ->groupBy('cluster')
                ->map(function ($items, $cluster) {
                    return [
                        'cluster' => 'C'.$cluster,
                        'label' => $items->first()->cluster_label,
                        'jumlah' => $items->count(),
                    ];
                })
                ->values()
                ->all();

            $this->table(
                [
                    'Cluster',
                    'Kategori',
                    'Jumlah Titik',
                ],
                $rows
            );

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
