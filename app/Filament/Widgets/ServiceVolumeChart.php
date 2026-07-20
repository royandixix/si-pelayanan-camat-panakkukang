<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use App\Models\ServiceApplication;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Builder;

class ServiceVolumeChart extends ChartWidget
{
    protected ?string $heading = 'Volume Permohonan per Seksi';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '360px';

    protected ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    public function getDescription(): ?string
    {
        return 'Perbandingan jumlah permohonan pada setiap seksi pelayanan.';
    }

    protected function getData(): array
    {
        $daftarSeksi = Section::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'code',
                'name',
            ]);

        $jumlahPermohonan = $daftarSeksi
            ->map(
                fn (Section $seksi): int =>
                    ServiceApplication::query()
                        ->whereHas(
                            'service',
                            fn (Builder $query): Builder =>
                                $query->where(
                                    'section_id',
                                    $seksi->id,
                                ),
                        )
                        ->count(),
            )
            ->all();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $jumlahPermohonan,
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                    'maxBarThickness' => 54,
                ],
            ],
            'labels' => $daftarSeksi
                ->map(
                    fn (Section $seksi): string =>
                        strtoupper(
                            (string) $seksi->code,
                        ),
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
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'autoSkip' => false,
                        'maxRotation' => 0,
                        'minRotation' => 0,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                        'stepSize' => 1,
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
