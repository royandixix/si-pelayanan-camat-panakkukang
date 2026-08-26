<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KMeansResult extends Model
{
    protected $table = 'kmeans_results';

    protected $fillable = [
        'kmeans_run_id',
        'dataset_name',
        'year',
        'month',
        'jumlah_pelayanan',
        'hari_aktif',
        'rata_rata_harian',
        'z_jumlah_pelayanan',
        'z_hari_aktif',
        'cluster',
        'cluster_label',
        'distance_to_centroid',
        'reference_label',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'month' => 'integer',
            'jumlah_pelayanan' => 'integer',
            'hari_aktif' => 'integer',
            'rata_rata_harian' => 'float',
            'z_jumlah_pelayanan' => 'float',
            'z_hari_aktif' => 'float',
            'cluster' => 'integer',
            'distance_to_centroid' => 'float',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(KMeansRun::class, 'kmeans_run_id');
    }
}
