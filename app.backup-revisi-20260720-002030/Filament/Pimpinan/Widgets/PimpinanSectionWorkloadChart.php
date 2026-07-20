<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\UserRole;
use App\Models\Section;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PimpinanSectionWorkloadChart extends ChartWidget
{
    protected static ?int $sort=3;

    protected int|string|array $columnSpan=1;

    protected ?string $heading='Perbandingan Beban Kerja Lima Seksi';

    protected ?string $description='Jumlah permohonan pelayanan pada setiap seksi.';

    protected function getData(): array
    {
        $sections=Section::query()
            ->where('is_active',true)
            ->orderBy('id')
            ->get();

        $volumes=DB::table('service_applications')
            ->join(
                'services',
                'service_applications.service_id',
                '=',
                'services.id',
            )
            ->select(
                'services.section_id',
                DB::raw('COUNT(service_applications.id) as total'),
            )
            ->groupBy('services.section_id')
            ->pluck('total','services.section_id');

        return [
            'datasets'=>[
                [
                    'label'=>'Jumlah Permohonan',
                    'data'=>$sections
                        ->map(
                            fn(Section $section): int=>
                                (int)($volumes[$section->id]??0),
                        )
                        ->all(),
                    'backgroundColor'=>'rgba(59,130,246,0.35)',
                    'borderColor'=>'#3b82f6',
                    'borderWidth'=>2,
                    'borderRadius'=>6,
                ],
            ],
            'labels'=>$sections
                ->map(
                    fn(Section $section): string=>
                        $this->shortSectionName($section->name),
                )
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis'=>'y',
            'plugins'=>[
                'legend'=>[
                    'display'=>false,
                ],
            ],
            'scales'=>[
                'x'=>[
                    'beginAtZero'=>true,
                    'ticks'=>[
                        'precision'=>0,
                    ],
                ],
            ],
        ];
    }

    private function shortSectionName(string $name): string
    {
        $normalized=Str::lower($name);

        return match(true){
            Str::contains($normalized,'pemberdayaan')=>'Pemberdayaan Masyarakat',
            Str::contains($normalized,'pemerintahan')=>'Pemerintahan',
            Str::contains($normalized,'ketenteraman'),
            Str::contains($normalized,'ketertiban')=>'Ketenteraman dan Ketertiban',
            Str::contains($normalized,'pelayanan')=>'Pelayanan Front Office',
            Str::contains($normalized,'kebersihan')=>'Kebersihan',
            default=>Str::limit($name,28),
        };
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User
            && $user->role===UserRole::PIMPINAN;
    }
}
