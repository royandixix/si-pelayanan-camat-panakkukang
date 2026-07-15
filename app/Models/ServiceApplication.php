<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_number',
        'user_id',
        'service_id',
        'assigned_admin_id',
        'status',
        'applicant_data',
        'applicant_notes',
        'internal_notes',
        'submitted_at',
        'verified_at',
        'completed_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'applicant_data' => 'array',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'completed_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class, 'application_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'application_id')
            ->latest('created_at');
    }

    public function queue(): HasOne
    {
        return $this->hasOne(ServiceQueue::class, 'application_id');
    }
}
