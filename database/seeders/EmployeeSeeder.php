<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'MUH. SYAFRIAL HARTAWAN',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'HUMAS',
                'display_order' => 1,
            ],
            [
                'name' => 'MUH. IKRAM ALGASALI',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI KETENTRAMAN DAN KETERTIBAN UMUM',
                'display_order' => 2,
            ],
            [
                'name' => 'HASPIDA',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI KETENTRAMAN DAN KETERTIBAN UMUM',
                'display_order' => 3,
            ],
            [
                'name' => 'APRIYANTO, SH',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEMBERDAYAAN MASYARAKAT DAN KESEJAHTARAAN SOSIAL',
                'display_order' => 4,
            ],
            [
                'name' => 'MUHAMMAD YUSUF',
                'position' => 'PENGADMINISTRASI PERKANTORAN',
                'work_unit' => 'SEKSI PEMBERDAYAAN MASYARAKAT DAN KESEJAHTARAAN SOSIAL',
                'display_order' => 5,
            ],
            [
                'name' => 'RISMAWATY , SE',
                'position' => 'PENATALAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI KETENTRAMAN DAN KETERTIBAN UMUM',
                'display_order' => 6,
            ],
            [
                'name' => 'ADE INAYAH BAHAR, S. KOM',
                'position' => 'PENATA KELOLA SISTEM DAN TEKNOLOGI INFORMASI',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 7,
            ],
            [
                'name' => 'ARDY ARSYAD HASAN',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 8,
            ],
            [
                'name' => 'INDAH ANGGRIANI SAWAL, S. IP',
                'position' => 'PENGGERAK SWADAYA MASYARAKAT AHLI PERTAMA',
                'work_unit' => 'SEKSI PEMBERDAYAAN MASYARAKAT DAN KESEJAHTARAAN SOSIAL',
                'display_order' => 9,
            ],
            [
                'name' => 'MILA NURMILA ALWI, SE',
                'position' => 'PENATA KELOLA SISTEM DAN TEKNOLOGI INFORMASI',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 10,
            ],
            [
                'name' => 'VIVI PURWANTI, S.E',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEMBERDAYAAN MASYARAKAT DAN KESEJAHTARAAN SOSIAL',
                'display_order' => 11,
            ],
            [
                'name' => 'ZULFIKAR LUTHFI, SH',
                'position' => 'KEPALA SEKSI KETENTRAMAN DAN KETERTIBAN UMUM',
                'work_unit' => 'SEKSI KETENTRAMAN DAN KETERTIBAN UMUM',
                'display_order' => 12,
            ],
            [
                'name' => 'HABRIANTO',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PENGELOLAAN KEBERSIHAN DAN PERTAMANA',
                'display_order' => 13,
            ],
            [
                'name' => 'ASRUDDIN RAUFUNG',
                'position' => 'PENGADMINISTRASI PERKANTORAN',
                'work_unit' => 'SEKSI PENGELOLAAN KEBERSIHAN DAN PERTAMANA',
                'display_order' => 14,
            ],
            [
                'name' => 'ALWAHDANIA, S. SOS',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEREKONOMIAN DAN PEMBANGUNAN',
                'display_order' => 15,
            ],
            [
                'name' => 'HASDIANA',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEMERINTAHAN',
                'display_order' => 16,
            ],
            [
                'name' => 'ANDIKA PERMANA PUTRA, S. SOS',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'HUMAS',
                'display_order' => 17,
            ],
            [
                'name' => 'MARVEN GURION LOHY, S. KOM',
                'position' => 'PENATA KELOLA SISTEM DAN TEKNOLOGI INFORMASI',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 18,
            ],
            [
                'name' => 'SHINTA WULANDARY',
                'position' => 'PENGADMINISTRASI PERKANTORAN',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 19,
            ],
            [
                'name' => 'FARIDAWATI',
                'position' => 'KEPALA SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 20,
            ],
            [
                'name' => 'ACHMAD NAIM KANE, ST',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 21,
            ],
            [
                'name' => 'MOH RIDWAN KALLO',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 22,
            ],
            [
                'name' => 'JAKA PRATAMA SANTOSA PUTRA, S.E',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PENGELOLAAN KEBERSIHAN DAN PERTAMANA',
                'display_order' => 23,
            ],
            [
                'name' => 'URWAH QARFILAH SASTRA FIANA, S. KOM',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PENGELOLAAN KEBERSIHAN DAN PERTAMANA',
                'display_order' => 24,
            ],
            [
                'name' => 'MUHAMMAD TAUFIK. S',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 25,
            ],
            [
                'name' => 'SURIANI',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 26,
            ],
            [
                'name' => 'NURUL ASMI',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 27,
            ],
            [
                'name' => 'SALEHUDDIN',
                'position' => 'PENGADMINISTRASI PERKANTORAN',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 28,
            ],
            [
                'name' => 'MUH HASWIN, SE',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 29,
            ],
            [
                'name' => 'RIO FADJRI, S. KOM',
                'position' => 'PENATA KELOLA SISTEM DAN TEKNOLOGI INFORMASI',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 30,
            ],
            [
                'name' => 'ARFANDY RHIDATULLAH MASYAR',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEMERINTAHAN',
                'display_order' => 31,
            ],
            [
                'name' => 'MUH ZULKIFLY K, SE',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEMERINTAHAN',
                'display_order' => 32,
            ],
            [
                'name' => 'MUHAMMAD HAYYIR K, SE',
                'position' => 'PENELAAH TEKNIS KEBIJAKAN',
                'work_unit' => 'SEKSI PEREKONOMIAN DAN PEMBANGUNAN',
                'display_order' => 33,
            ],
            [
                'name' => 'ASTRIUNI, A. MD',
                'position' => 'PENGELOLA LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN PERENCANAAN DAN KEUANGAN',
                'display_order' => 34,
            ],
            [
                'name' => 'MUHAMMAD NURDIN, S.M',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PENGELOLAAN KEBERSIHAN DAN PERTAMANA',
                'display_order' => 35,
            ],
            [
                'name' => 'BAMBANG SUPRYADI, S. AP',
                'position' => 'PENATA LAYANAN OPERASIONAL',
                'work_unit' => 'SUB BAGIAN UMUM DAN KEPEGAWAIAN',
                'display_order' => 36,
            ],
            [
                'name' => 'MAULIDA KHAIRUNNISYA',
                'position' => 'OPERATOR LAYANAN OPERASIONAL',
                'work_unit' => 'SEKSI PEREKONOMIAN DAN PEMBANGUNAN',
                'display_order' => 37,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::query()->updateOrCreate(
                [
                    'name' => $employee['name'],
                ],
                [
                    'position' => $employee['position'],
                    'work_unit' => $employee['work_unit'],
                    'display_order' => $employee['display_order'],
                    'is_active' => true,
                ],
            );
        }
    }
}