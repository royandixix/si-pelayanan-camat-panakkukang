<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN_SEKSI = 'admin_seksi';
    case ADMIN_PMKS = 'admin_pmks';
    case ADMIN_PEMERINTAHAN = 'admin_pemerintahan';
    case ADMIN_TRANTIB = 'admin_trantib';
    case ADMIN_PELAYANAN = 'admin_pelayanan';
    case ADMIN_KEBERSIHAN = 'admin_kebersihan';
    case PIMPINAN = 'pimpinan';
    case MASYARAKAT = 'masyarakat';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN_SEKSI => 'Admin Seksi',
            self::ADMIN_PMKS => 'Admin Seksi PMKS',
            self::ADMIN_PEMERINTAHAN => 'Admin Seksi Pemerintahan',
            self::ADMIN_TRANTIB => 'Admin Seksi Trantib',
            self::ADMIN_PELAYANAN => 'Admin Seksi Pelayanan',
            self::ADMIN_KEBERSIHAN => 'Admin Seksi Kebersihan',
            self::PIMPINAN => 'Pimpinan/Camat',
            self::MASYARAKAT => 'Masyarakat',
        };
    }

    public static function adminSeksiCases(): array
    {
        return [
            self::ADMIN_SEKSI,
            self::ADMIN_PMKS,
            self::ADMIN_PEMERINTAHAN,
            self::ADMIN_TRANTIB,
            self::ADMIN_PELAYANAN,
            self::ADMIN_KEBERSIHAN,
        ];
    }

    public static function adminSeksiValues(): array
    {
        return array_map(
            fn (self $role): string => $role->value,
            self::adminSeksiCases(),
        );
    }

    public function isAdminSeksi(): bool
    {
        return in_array(
            $this,
            self::adminSeksiCases(),
            true,
        );
    }

    public static function fromSectionName(?string $name): self
    {
        $name = mb_strtolower((string) $name);

        return match (true) {
            str_contains($name, 'pemberdayaan') => self::ADMIN_PMKS,
            str_contains($name, 'pemerintahan') => self::ADMIN_PEMERINTAHAN,
            str_contains($name, 'ketenteraman'),
            str_contains($name, 'ketertiban') => self::ADMIN_TRANTIB,
            str_contains($name, 'pelayanan') => self::ADMIN_PELAYANAN,
            str_contains($name, 'kebersihan') => self::ADMIN_KEBERSIHAN,
            default => self::ADMIN_SEKSI,
        };
    }

    public static function formOptions(): array
    {
        return [
            self::SUPER_ADMIN->value => self::SUPER_ADMIN->label(),
            self::ADMIN_PMKS->value => self::ADMIN_PMKS->label(),
            self::ADMIN_PEMERINTAHAN->value => self::ADMIN_PEMERINTAHAN->label(),
            self::ADMIN_TRANTIB->value => self::ADMIN_TRANTIB->label(),
            self::ADMIN_PELAYANAN->value => self::ADMIN_PELAYANAN->label(),
            self::ADMIN_KEBERSIHAN->value => self::ADMIN_KEBERSIHAN->label(),
            self::PIMPINAN->value => self::PIMPINAN->label(),
            self::MASYARAKAT->value => self::MASYARAKAT->label(),
        ];
    }
}
