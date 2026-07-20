<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Models\Section;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PimpinanStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Ringkasan Pelayanan';

    protected ?string $description =
        'Statistik pelayanan seluruh seksi Kantor Camat Panakkukang.';

    protected function getStats(): array
    {
        $total = ServiceApplication::query()->count('*');

        $menunggu = ServiceApplication::query()
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::VERIFICATION->value,
                ApplicationStatus::REVISION->value,
            ], 'and', false)
            ->count('*');

        $diproses = ServiceApplication::query()
            ->whereIn('status', [
                ApplicationStatus::APPROVED->value,
                ApplicationStatus::PROCESSING->value,
            ], 'and', false)
            ->count('*');

        $selesai = ServiceApplication::query()
            ->whereIn('status', [
                ApplicationStatus::COMPLETED->value,
                ApplicationStatus::COLLECTED->value,
            ], 'and', false)
            ->count('*');

        $ditolak = ServiceApplication::query()
            ->where(
                'status',
                '=',
                ApplicationStatus::REJECTED->value,
                'and',
            )
            ->count('*');

        $persentaseSelesai = $total > 0
            ? round(($selesai / $total) * 100, 1)
            : 0;

        return [
            Stat::make('Total Permohonan', $total)
                ->description('Seluruh permohonan pelayanan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', $menunggu)
                ->description('Perlu ditindaklanjuti petugas')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sedang Diproses', $diproses)
                ->description('Permohonan dalam proses pelayanan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Selesai', $selesai)
                ->description($persentaseSelesai . '% dari total permohonan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak', $ditolak)
                ->description('Permohonan tidak dapat dilanjutkan')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make(
                'Seksi Aktif',
                Section::query()
                    ->where('is_active', '=', true, 'and')
                    ->count('*'),
            )
                ->description('Unit kerja pelayanan aktif')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('gray'),
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === UserRole::PIMPINAN;
    }
}
