<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected static ?string $title = 'Tambah Data Layanan';

    protected function mutateFormDataBeforeCreate(
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

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan'),
            $this->getCreateAnotherFormAction()
                ->label('Simpan dan tambah lagi'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}