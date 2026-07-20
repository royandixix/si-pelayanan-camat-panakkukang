<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'employee_count',
        'daily_queue_quota',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'employee_count' => 'integer',
            'daily_queue_quota' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(ServiceQueue::class);
    }

    public function kMeansResults(): HasMany
    {
        return $this->hasMany(KMeansResult::class);
    }
}
