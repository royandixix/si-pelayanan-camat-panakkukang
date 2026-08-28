<?php

namespace App\Filament\Resources\AdminSeksis\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\AdminSeksis\AdminSeksiResource;
use App\Models\Section;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminSeksi extends EditRecord
{
    protected static string $resource = AdminSeksiResource::class;

    public function getTitle(): string
    {
        return 'Edit Admin Seksi';
    }

    protected function mutateFormDataBeforeSave(
        array $data,
    ): array {
        $section = Section::query()
            ->findOrFail($data['section_id']);

        $data['role'] = UserRole::fromSectionName(
            $section->name
        )->value;

        unset($data['password_confirmation']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Admin'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data Admin Seksi berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
