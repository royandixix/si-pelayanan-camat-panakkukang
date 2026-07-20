<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Ringkasan Pelayanan';

    protected ?string $description = 'Ringkasan data utama pelayanan masyarakat Kantor Camat Panakkukang.';

    /**
     * Jumlah kartu per baris responsif.
     */
    protected function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $totalMasyarakat = User::query()
            ->where('role', 'masyarakat')
            ->count();

        $totalSeksi = Section::query()
            ->where('is_active', true)
            ->count();

        $totalLayanan = Service::query()
            ->where('is_active', true)
            ->count();

        $totalPermohonan = ServiceApplication::query()->count();

        $menungguVerifikasi = ServiceApplication::query()
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::VERIFICATION->value,
            ])
            ->count();

        $sedangDiproses = ServiceApplication::query()
            ->where('status', ApplicationStatus::PROCESSING->value)
            ->count();

        $permohonanSelesai = ServiceApplication::query()
            ->where('status', ApplicationStatus::COMPLETED->value)
            ->count();

        $antreanHariIni = ServiceQueue::query()
            ->whereDate('created_at', today())
            ->count();

        return [
            Stat::make('Total Masyarakat', number_format($totalMasyarakat))
                ->description('Akun masyarakat terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->icon('heroicon-o-users')
                ->color('primary')
                ->chart([1, 1, 2, 2, 2, 3, max($totalMasyarakat, 1)]),

            Stat::make('Total Seksi', number_format($totalSeksi))
                ->description('Seksi pelayanan aktif')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->icon('heroicon-o-building-office-2')
                ->color('primary')
                ->chart([1, 1, 1, 2, 2, 3, max($totalSeksi, 1)]),

            Stat::make('Total Layanan', number_format($totalLayanan))
                ->description('Jenis layanan tersedia')
                ->descriptionIcon('heroicon-m-briefcase')
                ->icon('heroicon-o-briefcase')
                ->color('primary')
                ->chart([1, 2, 2, 3, 3, 4, max($totalLayanan, 1)]),

            Stat::make('Total Permohonan', number_format($totalPermohonan))
                ->description('Seluruh permohonan masuk')
                ->descriptionIcon('heroicon-m-document-text')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->chart([1, 1, 2, 2, 3, 3, max($totalPermohonan, 1)]),

            Stat::make('Menunggu Verifikasi', number_format($menungguVerifikasi))
                ->description('Permohonan perlu diperiksa')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->chart([0, 0, 1, 1, 1, 2, max($menungguVerifikasi, 1)]),

            Stat::make('Sedang Diproses', number_format($sedangDiproses))
                ->description('Dalam proses pelayanan')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->chart([0, 0, 0, 1, 1, 1, max($sedangDiproses, 1)]),

            Stat::make('Permohonan Selesai', number_format($permohonanSelesai))
                ->description('Pelayanan telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->chart([0, 0, 0, 1, 1, 2, max($permohonanSelesai, 1)]),

            Stat::make('Antrean Hari Ini', number_format($antreanHariIni))
                ->description('Antrean terdaftar hari ini')
                ->descriptionIcon('heroicon-m-queue-list')
                ->icon('heroicon-o-queue-list')
                ->color('primary')
                ->chart([0, 0, 1, 1, 2, 2, max($antreanHariIni, 1)]),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}