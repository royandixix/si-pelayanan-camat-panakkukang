<?php

namespace App\Filament\Pimpinan\Resources\ServiceReports\Pages;

use App\Enums\ApplicationStatus;
use App\Filament\Pimpinan\Resources\ServiceReports\ServiceReportResource;
use App\Models\Section;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListServiceReports extends ListRecords
{
    protected static string $resource =
        ServiceReportResource::class;

    protected static ?string $title =
        'Laporan Pelayanan';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewCsv')
                ->label('Pratinjau CSV')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->schema($this->previewFilterSchema())
                ->modalHeading('Pratinjau Laporan CSV')
                ->modalDescription(
                    'Pilih filter sebelum melihat hasil laporan.',
                )
                ->modalSubmitActionLabel('Lihat Pratinjau')
                ->action(function (array $data) {
                    return redirect()->to(
                        $this->previewUrl(
                            'pimpinan.laporan.preview.csv',
                            $data,
                        ),
                    );
                }),

            Action::make('previewPdf')
                ->label('Pratinjau PDF')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('danger')
                ->schema($this->previewFilterSchema())
                ->modalHeading('Pratinjau Laporan PDF')
                ->modalDescription(
                    'PDF akan dibuka di PDF Viewer browser.',
                )
                ->modalSubmitActionLabel('Buka PDF')
                ->action(function (array $data) {
                    return redirect()->to(
                        $this->previewUrl(
                            'pimpinan.laporan.preview.pdf',
                            $data,
                        ),
                    );
                }),
        ];
    }

    private function previewFilterSchema(): array
    {
        return [
            DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->native(false),

            DatePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->native(false)
                ->afterOrEqual('tanggal_mulai'),

            Select::make('section_id')
                ->label('Seksi')
                ->options(
                    Section::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all(),
                )
                ->searchable()
                ->preload(),

            Select::make('service_id')
                ->label('Jenis Layanan')
                ->options(
                    Service::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all(),
                )
                ->searchable()
                ->preload(),

            Select::make('status')
                ->label('Status Permohonan')
                ->options(
                    collect(ApplicationStatus::cases())
                        ->mapWithKeys(
                            fn (
                                ApplicationStatus $status,
                            ): array => [
                                $status->value =>
                                    (string) $status->getLabel(),
                            ],
                        )
                        ->all(),
                ),
        ];
    }

    private function previewUrl(
        string $routeName,
        array $data,
    ): string {
        $filters = array_filter(
            $data,
            fn (mixed $value): bool =>
                $value !== null
                && $value !== '',
        );

        $url = route($routeName);

        if ($filters === []) {
            return $url;
        }

        return $url . '?' . http_build_query($filters);
    }
}
