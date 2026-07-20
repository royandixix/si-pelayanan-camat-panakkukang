<?php

namespace App\Filament\Resources\ServiceQueues\Pages;

use App\Filament\Resources\ServiceQueues\ServiceQueueResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditServiceQueue extends EditRecord
{
    protected static string $resource = ServiceQueueResource::class;

    protected static ?string $title = 'Ubah Antrean Pelayanan';

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label('Lihat'),
            DeleteAction::make()
                ->label('Hapus')
                ->modalHeading('Hapus antrean pelayanan')
                ->modalDescription('Apakah Anda yakin ingin menghapus antrean pelayanan ini?')
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