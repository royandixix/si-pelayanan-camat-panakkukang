<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlackBoxTest extends Model
{
    protected $fillable = [
        'code',
        'module',
        'scenario',
        'test_input',
        'expected_result',
        'actual_result',
        'status',
        'tested_at',
    ];

    protected function casts(): array
    {
        return [
            'tested_at' => 'datetime',
        ];
    }
}
