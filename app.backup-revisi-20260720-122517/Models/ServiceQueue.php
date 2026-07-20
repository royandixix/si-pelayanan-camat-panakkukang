<?php

namespace App\Models;

use App\Enums\QueueStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'section_id',
        'service_id',
        'queue_date',
        'prefix',
        'sequence',
        'queue_number',
        'status',
        'registered_at',
        'called_at',
        'service_started_at',
        'served_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'queue_date' => 'date',
            'sequence' => 'integer',
            'status' => QueueStatus::class,
            'registered_at' => 'datetime',
            'called_at' => 'datetime',
            'service_started_at' => 'datetime',
            'served_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(ServiceApplication::class, 'application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
