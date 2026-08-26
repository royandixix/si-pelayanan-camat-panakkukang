<?php

namespace App\Filament\Resources\KMeansRuns\Pages;

use App\Filament\Resources\KMeansRuns\KMeansRunResource;
use App\Services\KMeansService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Throwable;

class ListKMeansRuns extends ListRecords
{
    protected static string $resource = KMeansRunResource::class;

    protected static ?string $title = 'Proses K-Means';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runKMeans')
                ->label('Jalankan Proses K-Means')
                ->icon('heroicon-o-play')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Jalankan Proses K-Means')
                ->modalDescription(
                    'Sistem akan memproses data valid menggunakan K = 3 dengan normalisasi Z-Score.'
                )
                ->action(function (): void {
                    try {
                        $run = app(KMeansService::class)->run(3);

                        Notification::make()
                            ->title('Proses K-Means berhasil')
                            ->body(
                                'Run #'.$run->id
                                .' selesai dengan '
                                .$run->total_points
                                .' titik data.'
                            )
                            ->success()
                            ->send();

                        $this->redirect(
                            KMeansRunResource::getUrl(
                                'view',
                                ['record' => $run->id]
                            )
                        );
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Proses K-Means gagal')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
