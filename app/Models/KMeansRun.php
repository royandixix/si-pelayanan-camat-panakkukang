<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KMeansRun extends Model
{
    protected $table = 'kmeans_runs';

    protected $fillable = [
        'k',
        'total_source_records',
        'valid_source_records',
        'excluded_records',
        'total_points',
        'features',
        'normalization',
        'iterations',
        'wcss',
        'silhouette_score',
        'cluster_centroids',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'k' => 'integer',
            'total_source_records' => 'integer',
            'valid_source_records' => 'integer',
            'excluded_records' => 'integer',
            'total_points' => 'integer',
            'features' => 'array',
            'iterations' => 'integer',
            'wcss' => 'float',
            'silhouette_score' => 'float',
            'cluster_centroids' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function results(): HasMany
    {
        return $this->hasMany(KMeansResult::class, 'kmeans_run_id');
    }
}
