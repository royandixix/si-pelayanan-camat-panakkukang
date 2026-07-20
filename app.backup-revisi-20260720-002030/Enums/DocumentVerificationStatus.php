<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DocumentVerificationStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case VALID = 'valid';
    case REVISION = 'revision';
    case INVALID = 'invalid';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Belum Diperiksa',
            self::VALID => 'Valid',
            self::REVISION => 'Perlu Diperbaiki',
            self::INVALID => 'Tidak Valid',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::VALID => 'success',
            self::REVISION => 'warning',
            self::INVALID => 'danger',
        };
    }
}