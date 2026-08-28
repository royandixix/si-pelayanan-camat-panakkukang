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

    protected string $view =
        'filament.resources.k-means-runs.pages.list-k-means-runs';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('runKMeans')
                ->label('Proses Ulang K-Means')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->disabled(
                    fn (): bool =>
                        ! app(KMeansService::class)
                            ->hasDatasetChanged()
                )
                ->requiresConfirmation()
                ->modalHeading('Proses Ulang K-Means')
                ->modalDescription(
                    'Proses hanya dilakukan apabila data sumber penelitian mengalami perubahan.'
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
                                [
                                    'record' => $run->id,
                                ]
                            )
                        );
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Proses K-Means tidak dijalankan')
                            ->body($e->getMessage())
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}
