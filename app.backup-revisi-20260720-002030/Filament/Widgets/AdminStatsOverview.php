<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    protected static bool $isLazy = false;

    protected ?string $heading = 'Ringkasan Pelayanan';

    protected ?string $description =
        'Ringkasan data pelayanan masyarakat Kantor Camat Panakkukang.';

    protected function getStats(): array
    {
        $totalMasyarakat = User::query()
            ->where('role', UserRole::MASYARAKAT->value)
            ->count();

        $totalSeksi = Section::query()
            ->where('is_active', true)
            ->count();

        $totalLayanan = Service::query()
            ->where('is_active', true)
            ->count();

        $totalPermohonan = ServiceApplication::query()
            ->count();

        $menungguVerifikasi = ServiceApplication::query()
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::VERIFICATION->value,
                ApplicationStatus::REVISION->value,
            ])
            ->count();

        $sedangDiproses = ServiceApplication::query()
            ->where(
                'status',
                ApplicationStatus::PROCESSING->value,
            )
            ->count();

        $permohonanSelesai = ServiceApplication::query()
            ->where(
                'status',
                ApplicationStatus::COMPLETED->value,
            )
            ->count();

        $antreanHariIni = ServiceQueue::query()
            ->whereDate('queue_date', today())
            ->count();

        return [
            Stat::make(
                'Total Masyarakat',
                $this->formatNumber($totalMasyarakat),
            )
                ->description('Akun masyarakat terdaftar')
                ->descriptionIcon(
                    'heroicon-m-users',
                    IconPosition::Before,
                )
                ->chart($this->dailyUserCounts())
                ->color('primary'),

            Stat::make(
                'Total Seksi',
                $this->formatNumber($totalSeksi),
            )
                ->description('Seksi pelayanan aktif')
                ->descriptionIcon(
                    'heroicon-m-building-office-2',
                    IconPosition::Before,
                )
                ->chart($this->constantChart($totalSeksi))
                ->color('primary'),

            Stat::make(
                'Total Layanan',
                $this->formatNumber($totalLayanan),
            )
                ->description('Jenis layanan tersedia')
                ->descriptionIcon(
                    'heroicon-m-rectangle-stack',
                    IconPosition::Before,
                )
                ->chart($this->constantChart($totalLayanan))
                ->color('primary'),

            Stat::make(
                'Total Permohonan',
                $this->formatNumber($totalPermohonan),
            )
                ->description('Seluruh permohonan masuk')
                ->descriptionIcon(
                    'heroicon-m-document-text',
                    IconPosition::Before,
                )
                ->chart($this->dailyApplicationCounts())
                ->color('primary'),

            Stat::make(
                'Menunggu Verifikasi',
                $this->formatNumber($menungguVerifikasi),
            )
                ->description('Permohonan yang perlu ditindaklanjuti')
                ->descriptionIcon(
                    'heroicon-m-clock',
                    IconPosition::Before,
                )
                ->chart(
                    $this->dailyApplicationCounts([
                        ApplicationStatus::SUBMITTED->value,
                        ApplicationStatus::VERIFICATION->value,
                        ApplicationStatus::REVISION->value,
                    ]),
                )
                ->color('warning'),

            Stat::make(
                'Sedang Diproses',
                $this->formatNumber($sedangDiproses),
            )
                ->description('Permohonan dalam proses pelayanan')
                ->descriptionIcon(
                    'heroicon-m-arrow-path',
                    IconPosition::Before,
                )
                ->chart(
                    $this->dailyApplicationCounts([
                        ApplicationStatus::PROCESSING->value,
                    ]),
                )
                ->color('info'),

            Stat::make(
                'Permohonan Selesai',
                $this->formatNumber($permohonanSelesai),
            )
                ->description('Pelayanan telah diselesaikan')
                ->descriptionIcon(
                    'heroicon-m-check-circle',
                    IconPosition::Before,
                )
                ->chart(
                    $this->dailyApplicationCounts([
                        ApplicationStatus::COMPLETED->value,
                    ]),
                )
                ->color('success'),

            Stat::make(
                'Antrean Hari Ini',
                $this->formatNumber($antreanHariIni),
            )
                ->description('Nomor antrean terdaftar hari ini')
                ->descriptionIcon(
                    'heroicon-m-queue-list',
                    IconPosition::Before,
                )
                ->chart($this->dailyQueueCounts())
                ->color('primary'),
        ];
    }

    private function dailyApplicationCounts(
        ?array $statuses = null,
    ): array {
        return collect(range(6, 0, -1))
            ->map(function (int $daysAgo) use ($statuses): int {
                return ServiceApplication::query()
                    ->when(
                        filled($statuses),
                        function (Builder $query) use ($statuses): void {
                            $query->whereIn('status', $statuses);
                        },
                    )
                    ->whereDate(
                        'created_at',
                        today()->subDays($daysAgo),
                    )
                    ->count();
            })
            ->all();
    }

    private function dailyUserCounts(): array
    {
        return collect(range(6, 0, -1))
            ->map(
                fn (int $daysAgo): int => User::query()
                    ->where(
                        'role',
                        UserRole::MASYARAKAT->value,
                    )
                    ->whereDate(
                        'created_at',
                        today()->subDays($daysAgo),
                    )
                    ->count(),
            )
            ->all();
    }

    private function dailyQueueCounts(): array
    {
        return collect(range(6, 0, -1))
            ->map(
                fn (int $daysAgo): int => ServiceQueue::query()
                    ->whereDate(
                        'queue_date',
                        today()->subDays($daysAgo),
                    )
                    ->count(),
            )
            ->all();
    }

    private function constantChart(int $value): array
    {
        return array_fill(0, 7, $value);
    }

    private function formatNumber(int $value): string
    {
        return number_format(
            num: $value,
            decimals: 0,
            decimal_separator: ',',
            thousands_separator: '.',
        );
    }

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}