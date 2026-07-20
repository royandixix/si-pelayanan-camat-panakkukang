<?php

namespace App\Filament\Widgets;

use App\Models\ServiceApplication;
use Filament\Widgets\ChartWidget;

class ApplicationStatusChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Permohonan';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '360px';

    protected ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    public function getDescription(): ?string
    {
        return 'Komposisi permohonan berdasarkan status pelayanan.';
    }

    protected function getData(): array
    {
        $daftarStatus = [
            'draft' => 'Draf',
            'submitted' => 'Diajukan',
            'verification' => 'Verifikasi',
            'revision' => 'Perlu Revisi',
            'processing' => 'Diproses',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            'collected' => 'Diambil',
        ];

        $jumlahStatus = ServiceApplication::query()
            ->selectRaw('status, COUNT(*) as total')
            ->whereIn(
                'status',
                array_keys($daftarStatus),
            )
            ->groupBy('status')
            ->pluck(
                'total',
                'status',
            );

        $label = [];
        $data = [];
        $warna = [];

        $daftarWarna = [
            'draft' => 'rgba(100, 116, 139, 0.85)',
            'submitted' => 'rgba(59, 130, 246, 0.85)',
            'verification' => 'rgba(245, 158, 11, 0.85)',
            'revision' => 'rgba(249, 115, 22, 0.85)',
            'processing' => 'rgba(14, 165, 233, 0.85)',
            'approved' => 'rgba(16, 185, 129, 0.85)',
            'rejected' => 'rgba(239, 68, 68, 0.85)',
            'completed' => 'rgba(34, 197, 94, 0.85)',
            'collected' => 'rgba(139, 92, 246, 0.85)',
        ];

        foreach ($daftarStatus as $kode => $nama) {
            $jumlah = (int) (
                $jumlahStatus[$kode] ?? 0
            );

            if ($jumlah === 0) {
                continue;
            }

            $label[] = $nama;
            $data[] = $jumlah;
            $warna[] = $daftarWarna[$kode];
        }

        if ($data === []) {
            $label = [
                'Belum Ada Data',
            ];

            $data = [
                1,
            ];

            $warna = [
                'rgba(100, 116, 139, 0.35)',
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan',
                    'data' => $data,
                    'backgroundColor' => $warna,
                    'borderWidth' => 0,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $label,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'cutout' => '68%',
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'boxHeight' => 12,
                        'padding' => 15,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
