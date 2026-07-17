<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Section;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PimpinanStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading='Ringkasan Pelayanan';
    protected ?string $description='Statistik pelayanan seluruh seksi Kantor Camat Panakkukang.';
    protected int|string|array $columnSpan='full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Permohonan',ServiceApplication::count())
                ->description('Seluruh permohonan pelayanan')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Menunggu Verifikasi',ServiceApplication::whereIn('status',[
                ApplicationStatus::SUBMITTED,
                ApplicationStatus::VERIFICATION,
            ])->count())
                ->description('Permohonan yang perlu ditindaklanjuti')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Sedang Diproses',ServiceApplication::where('status',ApplicationStatus::PROCESSING)->count())
                ->description('Permohonan dalam proses pelayanan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            Stat::make('Permohonan Selesai',ServiceApplication::whereIn('status',[
                ApplicationStatus::COMPLETED,
                ApplicationStatus::COLLECTED,
            ])->count())
                ->description('Pelayanan telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Permohonan Ditolak',ServiceApplication::where('status',ApplicationStatus::REJECTED)->count())
                ->description('Permohonan yang ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Seksi Aktif',Section::where('is_active',true)->count())
                ->description('Seksi pelayanan yang aktif')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('gray'),
        ];
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User&&$user->isPimpinan();
    }
}