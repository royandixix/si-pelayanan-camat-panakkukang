<?php

namespace App\Filament\Resources\KMeansRuns\Pages;

use App\Filament\Resources\KMeansRuns\KMeansRunResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKMeansRun extends EditRecord
{
    protected static string $resource = KMeansRunResource::class;

    protected static ?string $title = 'Ubah Proses K-Means';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
            DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Simpan perubahan'),
            $this->getCancelFormAction()
                ->label('Batal'),
        ];
    }
}