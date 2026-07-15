<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KMeansResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'k_means_run_id',
        'section_id',
        'service_volume',
        'queue_volume',
        'total_volume',
        'employee_count',
        'cluster_number',
        'centroid',
        'distance_to_centroid',
        'workload_category',
        'rank',
        'recommended_employee_change',
        'recommendation',
    ];

    protected function casts(): array
    {
        return [
            'service_volume' => 'integer',
            'queue_volume' => 'integer',
            'total_volume' => 'integer',
            'employee_count' => 'integer',
            'cluster_number' => 'integer',
            'centroid' => 'decimal:6',
            'distance_to_centroid' => 'decimal:6',
            'rank' => 'integer',
            'recommended_employee_change' => 'integer',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(KMeansRun::class, 'k_means_run_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
