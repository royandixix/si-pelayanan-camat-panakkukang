<?php

namespace App\Filament\Resources\ServiceApplications\Pages;

use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceApplication extends CreateRecord
{
    protected static string $resource = ServiceApplicationResource::class;

    protected static ?string $title = 'Tambah Permohonan Layanan';

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