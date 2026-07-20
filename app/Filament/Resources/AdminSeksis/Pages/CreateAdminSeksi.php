<?php

namespace App\Filament\Resources\AdminSeksis\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\AdminSeksis\AdminSeksiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminSeksi extends CreateRecord
{
    protected static string $resource = AdminSeksiResource::class;

    protected static bool $canCreateAnother = false;

    public function getTitle(): string
    {
        return 'Tambah Admin Seksi';
    }

    protected function mutateFormDataBeforeCreate(
        array $data,
    ): array {
        $data['role'] = UserRole::ADMIN_SEKSI->value;
        $data['is_active'] = $data['is_active'] ?? true;
        $data['email_verified_at'] = now();

        unset($data['password_confirmation']);

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Admin Seksi berhasil dibuat';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
