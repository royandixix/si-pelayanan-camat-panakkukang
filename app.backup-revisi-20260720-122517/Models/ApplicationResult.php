<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'uploaded_by',
        'original_name',
        'path',
        'disk',
        'mime_type',
        'size_bytes',
        'notes',
        'published_at',
        'download_count',
        'last_downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
            'published_at' => 'datetime',
            'download_count' => 'integer',
            'last_downloaded_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            ServiceApplication::class,
            'application_id',
        );
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by',
        );
    }
}
