<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchDatasetRecord extends Model
{
    protected $fillable = [
        'section_id',
        'service_id',
        'dataset_name',
        'source_file',
        'source_row_no',
        'record_date',
        'raw_date',
        'subject_name',
        'description',
        'validation_status',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'source_row_no' => 'integer',
        ];
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
