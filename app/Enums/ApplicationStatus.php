<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ApplicationStatus: string implements HasLabel, HasColor
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case VERIFICATION = 'verification';
    case REVISION = 'revision';
    case PROCESSING = 'processing';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case COMPLETED = 'completed';
    case COLLECTED = 'collected';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draf',
            self::SUBMITTED => 'Diajukan',
            self::VERIFICATION => 'Menunggu Verifikasi',
            self::REVISION => 'Dokumen Perlu Diperbaiki',
            self::PROCESSING => 'Sedang Diproses',
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::COMPLETED => 'Selesai',
            self::COLLECTED => 'Sudah Diambil',
        };
    }

    public function label(): string
    {
        return $this->getLabel();
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SUBMITTED => 'primary',
            self::VERIFICATION => 'warning',
            self::REVISION => 'danger',
            self::PROCESSING => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::COMPLETED => 'success',
            self::COLLECTED => 'gray',
        };
    }
}