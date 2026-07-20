<?php

namespace App\Filament\Resources\ServiceRequirements\Pages;

use App\Filament\Resources\ServiceRequirements\ServiceRequirementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceRequirement extends CreateRecord
{
    protected static string $resource = ServiceRequirementResource::class;

    protected static ?string $title = 'Tambah Persyaratan Layanan';

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