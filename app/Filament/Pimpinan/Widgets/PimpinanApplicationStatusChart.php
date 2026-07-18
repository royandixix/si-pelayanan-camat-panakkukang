<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PimpinanApplicationStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Distribusi Status Permohonan';

    protected ?string $description =
        'Komposisi seluruh permohonan berdasarkan status pelayanan.';

    protected function getData(): array
    {
        $statuses = [
            ApplicationStatus::SUBMITTED,
            ApplicationStatus::VERIFICATION,
            ApplicationStatus::REVISION,
            ApplicationStatus::APPROVED,
            ApplicationStatus::PROCESSING,
            ApplicationStatus::COMPLETED,
            ApplicationStatus::COLLECTED,
            ApplicationStatus::REJECTED,
        ];

        $data = array_map(
            fn (ApplicationStatus $status): int =>
                ServiceApplication::query()
                    ->where('status', '=', $status->value, 'and')
                    ->count('*'),
            $statuses,
        );

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6',
                        '#f59e0b',
                        '#fb923c',
                        '#14b8a6',
                        '#0ea5e9',
                        '#22c55e',
                        '#10b981',
                        '#ef4444',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_map(
                fn (ApplicationStatus $status): string =>
                    (string) $status->getLabel(),
                $statuses,
            ),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 14,
                    ],
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === UserRole::PIMPINAN;
    }
}
