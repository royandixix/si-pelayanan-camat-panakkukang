<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class AdminSeksiStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    protected static bool $isLazy = false;

    protected ?string $heading = 'Ringkasan Pelayanan';

    protected ?string $description = 'Statistik pelayanan pada seksi yang Anda tangani.';

    protected function getStats(): array
    {
        $sectionId = auth()->user()?->section_id;

        $totalPermohonan = $this->applicationQuery($sectionId)->count();

        $menungguVerifikasi = $this->applicationQuery($sectionId)
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::VERIFICATION->value,
                ApplicationStatus::REVISION->value,
            ])
            ->count();

        $sedangDiproses = $this->applicationQuery($sectionId)
            ->where('status', ApplicationStatus::PROCESSING->value)
            ->count();

        $permohonanSelesai = $this->applicationQuery($sectionId)
            ->where('status', ApplicationStatus::COMPLETED->value)
            ->count();

        $ditolak = $this->applicationQuery($sectionId)
            ->where('status', ApplicationStatus::REJECTED->value)
            ->count();

        $antreanHariIni = ServiceQueue::query()
            ->where('section_id', $sectionId)
            ->whereDate('queue_date', today())
            ->count();

        $totalAntrean = ServiceQueue::query()
            ->where('section_id', $sectionId)
            ->count();

        return [
            Stat::make('Total Permohonan', $this->formatNumber($totalPermohonan))
                ->description('Semua permohonan pada seksi ini')
                ->descriptionIcon('heroicon-m-document-text', IconPosition::Before)
                ->chart($this->dailyApplicationCounts())
                ->color('primary'),

            Stat::make('Menunggu Verifikasi', $this->formatNumber($menungguVerifikasi))
                ->description('Perlu segera ditindaklanjuti')
                ->descriptionIcon('heroicon-m-clock', IconPosition::Before)
                ->chart($this->dailyApplicationCounts([
                    ApplicationStatus::SUBMITTED->value,
                    ApplicationStatus::VERIFICATION->value,
                    ApplicationStatus::REVISION->value,
                ]))
                ->color('warning'),

            Stat::make('Sedang Diproses', $this->formatNumber($sedangDiproses))
                ->description('Dalam proses pelayanan')
                ->descriptionIcon('heroicon-m-arrow-path', IconPosition::Before)
                ->chart($this->dailyApplicationCounts([
                    ApplicationStatus::PROCESSING->value,
                ]))
                ->color('info'),

            Stat::make('Permohonan Selesai', $this->formatNumber($permohonanSelesai))
                ->description('Pelayanan telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-circle', IconPosition::Before)
                ->chart($this->dailyApplicationCounts([
                    ApplicationStatus::COMPLETED->value,
                ]))
                ->color('success'),

            Stat::make('Permohonan Ditolak', $this->formatNumber($ditolak))
                ->description('Permohonan yang ditolak')
                ->descriptionIcon('heroicon-m-x-circle', IconPosition::Before)
                ->chart($this->dailyApplicationCounts([
                    ApplicationStatus::REJECTED->value,
                ]))
                ->color('danger'),

            Stat::make('Antrean Hari Ini', $this->formatNumber($antreanHariIni))
                ->description('Antrean pada hari ini')
                ->descriptionIcon('heroicon-m-queue-list', IconPosition::Before)
                ->chart($this->dailyQueueCounts())
                ->color('primary'),

            Stat::make('Total Antrean', $this->formatNumber($totalAntrean))
                ->description('Seluruh antrean terdaftar')
                ->descriptionIcon('heroicon-m-bars-3-bottom-left', IconPosition::Before)
                ->chart($this->dailyQueueCounts())
                ->color('gray'),
        ];
    }

    private function applicationQuery(?int $sectionId): Builder
    {
        return ServiceApplication::query()->whereHas(
            'service',
            fn (Builder $query): Builder => $query->where('section_id', $sectionId),
        );
    }

    private function dailyApplicationCounts(?array $statuses = null): array
    {
        $sectionId = auth()->user()?->section_id;

        return collect(range(6, 0))
            ->map(function (int $daysAgo) use ($sectionId, $statuses): int {
                return $this->applicationQuery($sectionId)
                    ->when(
                        filled($statuses),
                        fn (Builder $query): Builder => $query->whereIn('status', $statuses),
                    )
                    ->whereDate('created_at', today()->subDays($daysAgo))
                    ->count();
            })
            ->all();
    }

    private function dailyQueueCounts(): array
    {
        $sectionId = auth()->user()?->section_id;

        return collect(range(6, 0))
            ->map(
                fn (int $daysAgo): int => ServiceQueue::query()
                    ->where('section_id', $sectionId)
                    ->whereDate('queue_date', today()->subDays($daysAgo))
                    ->count(),
            )
            ->all();
    }

    private function formatNumber(int $value): string
    {
        return number_format($value, 0, ',', '.');
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdminSeksi() ?? false;
    }
}