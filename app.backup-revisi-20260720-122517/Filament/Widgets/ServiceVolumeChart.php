<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\ServiceApplication;
use Filament\Widgets\ChartWidget;

class ServiceVolumeChart extends ChartWidget
{
    protected ?string $heading =
        'Volume Permohonan per Seksi';

    protected string $color = 'primary';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected ?string $pollingInterval = '60s';

    protected ?string $maxHeight = '330px';

    protected static bool $isLazy = false;

    protected bool $isCollapsible = true;

    public function getDescription(): ?string
    {
        return 'Perbandingan jumlah permohonan pada setiap seksi pelayanan.';
    }

    protected function getData(): array
    {
        $sections = Section::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $applicationCounts = ServiceApplication::query()
            ->join(
                'services',
                'services.id',
                '=',
                'service_applications.service_id',
            )
            ->selectRaw(
                'services.section_id AS section_id, COUNT(*) AS total',
            )
            ->groupBy('services.section_id')
            ->pluck('total', 'section_id');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $sections
                        ->map(
                            fn (Section $section): int =>
                                (int) (
                                    $applicationCounts[$section->id]
                                    ?? 0
                                ),
                        )
                        ->all(),
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
            ],
            'labels' => $sections
                ->map(
                    fn (Section $section): string =>
                        filled($section->code)
                            ? $section->code
                            : $section->name,
                )
                ->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'displayColors' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
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
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}