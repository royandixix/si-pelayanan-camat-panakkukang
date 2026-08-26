<?php

namespace App\Filament\Resources\KMeansResults\Pages;

use App\Filament\Resources\KMeansResults\KMeansResultResource;
use Filament\Resources\Pages\EditRecord;

class EditKMeansResult extends EditRecord
{
    protected static string $resource =
        KMeansResultResource::class;

    protected static ?string $title =
        'Validasi Label Referensi';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            'reference_label' => $data['reference_label'],
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Label Referensi'),

            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
