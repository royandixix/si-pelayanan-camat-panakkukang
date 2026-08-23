<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'code',
        'name',
        'slug',
        'description',
        'form_schema',
        'service_standard',
        'queue_enabled',
        'processing_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'form_schema' => 'array',
            'service_standard' => 'array',
            'queue_enabled' => 'boolean',
            'processing_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ServiceRequirement::class)
            ->orderBy('sort_order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ServiceApplication::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(ServiceQueue::class);
    }
}