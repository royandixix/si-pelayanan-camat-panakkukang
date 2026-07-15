<?php

namespace App\Enums;

enum QueueStatus: string
{
    case WAITING = 'waiting';
    case CALLED = 'called';
    case SERVING = 'serving';
    case COMPLETED = 'completed';
    case SKIPPED = 'skipped';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Menunggu',
            self::CALLED => 'Dipanggil',
            self::SERVING => 'Sedang Dilayani',
            self::COMPLETED => 'Selesai',
            self::SKIPPED => 'Terlewati',
            self::CANCELLED => 'Dibatalkan',
        };
    }
}
