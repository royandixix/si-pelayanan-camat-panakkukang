<?php

namespace App\Filament\Resources\KMeansRuns\Pages;

use App\Filament\Resources\KMeansRuns\KMeansRunResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKMeansRun extends CreateRecord
{
    protected static string $resource = KMeansRunResource::class;

    protected static ?string $title = 'Buat Proses K-Means';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Simpan'),
            $this->getCreateAnotherFormAction()
                ->label('Simpan dan buat lagi'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}