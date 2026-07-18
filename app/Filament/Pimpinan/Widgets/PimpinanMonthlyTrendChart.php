<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\UserRole;
use App\Models\ServiceApplication;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PimpinanMonthlyTrendChart extends ChartWidget
{
    protected static ?int $sort=4;

    protected int|string|array $columnSpan='full';

    protected ?string $heading='Tren Permohonan Enam Bulan Terakhir';

    protected ?string $description='Perkembangan jumlah permohonan pelayanan setiap bulan.';

    protected function getData(): array
    {
        $months=collect(range(5,0))
            ->map(fn(int $month): Carbon=>now()->startOfMonth()->subMonths($month));

        $labels=$months
            ->map(fn(Carbon $month): string=>$month->translatedFormat('M Y'))
            ->all();

        $data=$months
            ->map(function(Carbon $month): int {
                return ServiceApplication::query()
                    ->whereBetween('created_at',[
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth(),
                    ])
                    ->count();
            })
            ->all();

        return [
            'datasets'=>[
                [
                    'label'=>'Jumlah Permohonan',
                    'data'=>$data,
                    'borderColor'=>'#2563eb',
                    'backgroundColor'=>'rgba(37,99,235,0.15)',
                    'fill'=>true,
                    'tension'=>0.35,
                    'pointRadius'=>4,
                    'pointHoverRadius'=>6,
                ],
            ],
            'labels'=>$labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins'=>[
                'legend'=>[
                    'display'=>false,
                ],
            ],
            'scales'=>[
                'y'=>[
                    'beginAtZero'=>true,
                    'ticks'=>[
                        'precision'=>0,
                    ],
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User
            && $user->role===UserRole::PIMPINAN;
    }
}
