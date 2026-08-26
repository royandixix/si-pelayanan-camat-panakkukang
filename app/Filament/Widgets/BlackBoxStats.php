<?php

namespace App\Filament\Widgets;

use App\Models\BlackBoxTest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlackBoxStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = BlackBoxTest::count();

        $lulus = BlackBoxTest::where(
            'status',
            'lulus'
        )->count();

        $gagal = BlackBoxTest::where(
            'status',
            'gagal'
        )->count();

        $belum = BlackBoxTest::where(
            'status',
            'belum_diuji'
        )->count();

        $persentase = $total > 0
            ? ($lulus / $total) * 100
            : 0;

        return [
            Stat::make('Total Pengujian', $total),

            Stat::make('Lulus', $lulus),

            Stat::make('Gagal', $gagal),

            Stat::make('Belum Diuji', $belum),

            Stat::make(
                'Persentase Keberhasilan',
                number_format($persentase, 2).'%'
            ),
        ];
    }
}
