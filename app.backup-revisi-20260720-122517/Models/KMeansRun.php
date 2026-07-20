<?php

namespace App\Models;

use App\Enums\KMeansRunStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KMeansRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_start',
        'period_end',
        'cluster_count',
        'status',
        'iterations',
        'wcss',
        'silhouette_score',
        'davies_bouldin_index',
        'input_snapshot',
        'executed_by',
        'executed_at',
        'error_message',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'cluster_count' => 'integer',
            'status' => KMeansRunStatus::class,
            'iterations' => 'integer',
            'wcss' => 'decimal:6',
            'silhouette_score' => 'decimal:6',
            'davies_bouldin_index' => 'decimal:6',
            'input_snapshot' => 'array',
            'executed_at' => 'datetime',
        ];
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'executed_by',
        );
    }

    public function results(): HasMany
    {
        return $this->hasMany(KMeansResult::class)
            ->orderBy('rank');
    }
}