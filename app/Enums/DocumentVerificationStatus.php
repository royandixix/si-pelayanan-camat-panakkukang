<?php

namespace App\Enums;

enum DocumentVerificationStatus: string
{
    case PENDING = 'pending';
    case VALID = 'valid';
    case INVALID = 'invalid';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Belum Diperiksa',
            self::VALID => 'Valid',
            self::INVALID => 'Tidak Valid',
        };
    }
}
