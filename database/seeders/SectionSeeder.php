<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'code' => 'PMKS',
                'name' => 'Seksi Pemberdayaan Masyarakat dan Kesejahteraan Sosial',
                'employee_count' => 0,
                'daily_queue_quota' => null,
            ],
            [
                'code' => 'PEM',
                'name' => 'Seksi Pemerintahan',
                'employee_count' => 0,
                'daily_queue_quota' => null,
            ],
            [
                'code' => 'TRANTIB',
                'name' => 'Seksi Ketenteraman dan Ketertiban Umum',
                'employee_count' => 0,
                'daily_queue_quota' => null,
            ],
            [
                'code' => 'PELAYANAN',
                'name' => 'Seksi Pelayanan (Front Office)',
                'employee_count' => 0,
                'daily_queue_quota' => 30,
            ],
            [
                'code' => 'KEBERSIHAN',
                'name' => 'Seksi Kebersihan',
                'employee_count' => 0,
                'daily_queue_quota' => null,
            ],
        ];

        foreach ($sections as $section) {
            Section::query()->updateOrCreate(
                ['code' => $section['code']],
                $section + ['is_active' => true],
            );
        }
    }
}
