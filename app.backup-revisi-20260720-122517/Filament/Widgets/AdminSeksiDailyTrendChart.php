<?php

namespace App\Filament\Widgets;

use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class AdminSeksiDailyTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tren Pelayanan 7 Hari Terakhir';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    protected ?string $pollingInterval = '60s';

    protected ?string $maxHeight = '330px';

    protected static bool $isLazy = false;

    protected bool $isCollapsible = true;

    public function getDescription(): ?string
    {
        return 'Perbandingan permohonan dan antrean harian pada seksi Anda.';
    }

    protected function getData(): array
    {
        $sectionId = auth()->user()?->section_id;

        $labels = collect(range(6, 0))
            ->map(fn (int $daysAgo): string => today()->subDays($daysAgo)->translatedFormat('d M'))
            ->all();

        $applications = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($sectionId): int {
                return ServiceApplication::query()
                    ->whereHas(
                        'service',
                        fn (Builder $query): Builder => $query->where('section_id', $sectionId),
                    )
                    ->whereDate('created_at', today()->subDays($daysAgo))
                    ->count();
            })
            ->all();

        $queues = collect(range(6, 0))
            ->map(
                fn (int $daysAgo): int => ServiceQueue::query()
                    ->where('section_id', $sectionId)
                    ->whereDate('queue_date', today()->subDays($daysAgo))
                    ->count(),
            )
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Permohonan',
                    'data' => $applications,
                    'borderColor' => '#2563eb',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Antrean',
                    'data' => $queues,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdminSeksi() ?? false;
    }
}