<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN_SEKSI = 'admin_seksi';
    case PIMPINAN = 'pimpinan';
    case MASYARAKAT = 'masyarakat';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN_SEKSI => 'Admin Seksi',
            self::PIMPINAN => 'Pimpinan/Camat',
            self::MASYARAKAT => 'Masyarakat',
        };
    }
}
