<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Models\Section;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PimpinanSectionWorkloadChart extends ChartWidget
{
    protected ?string $heading='Perbandingan Beban Kerja Lima Seksi';
    protected ?string $description='Jumlah permohonan pelayanan pada setiap seksi.';
    protected int|string|array $columnSpan=1;

    protected function getData(): array
    {
        $sections=Section::query()
            ->where('is_active',true)
            ->orderBy('id')
            ->get();

        $volumes=DB::table('service_applications')
            ->join('services','service_applications.service_id','=','services.id')
            ->select('services.section_id',DB::raw('COUNT(service_applications.id) as total'))
            ->whereNull('service_applications.deleted_at')
            ->groupBy('services.section_id')
            ->pluck('total','services.section_id');

        return [
            'datasets'=>[
                [
                    'label'=>'Jumlah Permohonan',
                    'data'=>$sections
                        ->map(fn(Section $section): int=>(int)($volumes[$section->id]??0))
                        ->all(),
                ],
            ],
            'labels'=>$sections
                ->map(fn(Section $section): string=>$section->name)
                ->all(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User&&$user->isPimpinan();
    }
}