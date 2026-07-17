<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\ApplicationStatus;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class PimpinanApplicationStatusChart extends ChartWidget
{
    protected ?string $heading='Distribusi Status Permohonan';
    protected ?string $description='Komposisi seluruh permohonan berdasarkan status pelayanan.';
    protected int|string|array $columnSpan=1;

    protected function getData(): array
    {
        $statuses=[
            ApplicationStatus::SUBMITTED,
            ApplicationStatus::VERIFICATION,
            ApplicationStatus::REVISION,
            ApplicationStatus::APPROVED,
            ApplicationStatus::PROCESSING,
            ApplicationStatus::COMPLETED,
            ApplicationStatus::COLLECTED,
            ApplicationStatus::REJECTED,
        ];

        return [
            'datasets'=>[
                [
                    'label'=>'Permohonan',
                    'data'=>array_map(
                        fn(ApplicationStatus $status): int=>ServiceApplication::where('status',$status)->count(),
                        $statuses,
                    ),
                ],
            ],
            'labels'=>array_map(
                fn(ApplicationStatus $status): string=>$status->label(),
                $statuses,
            ),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User&&$user->isPimpinan();
    }
}