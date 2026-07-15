<?php

namespace App\Filament\Resources\ServiceQueues\Pages;

use App\Filament\Resources\ServiceQueues\ServiceQueueResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceQueue extends CreateRecord
{
    protected static string $resource = ServiceQueueResource::class;

    protected static ?string $title = 'Tambah Antrean Pelayanan';

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