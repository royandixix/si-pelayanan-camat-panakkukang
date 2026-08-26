<?php

namespace App\Filament\Resources\BlackBoxTests\Pages;

use App\Filament\Resources\BlackBoxTests\BlackBoxTestResource;
use Filament\Resources\Pages\EditRecord;

class EditBlackBoxTest extends EditRecord
{
    protected static string $resource =
        BlackBoxTestResource::class;

    protected static ?string $title =
        'Hasil Black Box Testing';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            'actual_result' => $data['actual_result'],
            'status' => $data['status'],
            'tested_at' => now(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan Hasil Pengujian'),

            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}
