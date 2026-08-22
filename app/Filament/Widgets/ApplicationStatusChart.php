<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\ServiceApplication;
use Filament\Widgets\ChartWidget;

class ApplicationStatusChart extends ChartWidget
{
    protected ?string $heading =
        'Distribusi Status Permohonan';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = '60s';

    protected ?string $maxHeight = '330px';

    protected static bool $isLazy = false;

    protected bool $isCollapsible = true;

    public function getDescription(): ?string
    {
        return 'Komposisi permohonan berdasarkan status pelayanan.';
    }

    protected function getData(): array
    {
        $statuses = [
            ApplicationStatus::SUBMITTED->value =>
                'Diajukan',

            ApplicationStatus::VERIFICATION->value =>
                'Menunggu Verifikasi',

            ApplicationStatus::REVISION->value =>
                'Dokumen Perlu Diperbaiki',

            ApplicationStatus::PROCESSING->value =>
                'Diproses',

            ApplicationStatus::APPROVED->value =>
                'Disetujui',

            ApplicationStatus::REJECTED->value =>
                'Ditolak',

            ApplicationStatus::COMPLETED->value =>
                'Selesai',
        ];

        $counts = ServiceApplication::query()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => collect(array_keys($statuses))
                        ->map(
                            fn (string $status): int =>
                                (int) ($counts[$status] ?? 0),
                        )
                        ->all(),
                    'backgroundColor' => [
                        '#2563eb',
                        '#f59e0b',
                        '#f97316',
                        '#7c3aed',
                        '#0891b2',
                        '#dc2626',
                        '#16a34a',
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 10,
                ],
            ],
            'labels' => array_values($statuses),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '68%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'padding' => 18,
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#0f172a',
                    'padding' => 12,
                    'cornerRadius' => 10,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}