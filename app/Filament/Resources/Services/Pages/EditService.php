<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'Ubah Data Layanan';

    protected function mutateFormDataBeforeSave(
        array $data,
    ): array {
        $user = auth()->user();

        if ($user?->isAdminSeksi()) {
            abort_if(
                $user->section_id === null,
                403
            );

            $data['section_id'] = $user->section_id;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
            DeleteAction::make()
                ->label('Hapus')
                ->visible(
                    fn (): bool =>
                        auth()->user()?->isSuperAdmin() ?? false
                )
                ->modalHeading('Hapus data layanan')
                ->modalDescription('Apakah Anda yakin ingin menghapus data layanan ini?')
                ->modalSubmitActionLabel('Hapus')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Perubahan'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}