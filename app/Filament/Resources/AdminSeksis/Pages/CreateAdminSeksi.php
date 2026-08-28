<?php

namespace App\Filament\Resources\AdminSeksis\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\AdminSeksis\AdminSeksiResource;
use App\Models\Section;
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
        $section = Section::query()
            ->findOrFail($data['section_id']);

        $data['role'] = UserRole::fromSectionName(
            $section->name
        )->value;

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
