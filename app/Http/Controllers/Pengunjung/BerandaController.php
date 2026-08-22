<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Models\ServiceQueue;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function index(): View
    {
        $layanan = Service::query()
            ->with('section')
            ->withCount('requirements')
            ->where('is_active', true)
            ->orderBy('name')
            ->take(6)
            ->get();

        $jumlahLayanan = Service::query()->where('is_active', true)->count();
        $jumlahSeksi = Section::query()->where('is_active', true)->count();
        $jumlahPermohonan = ServiceApplication::query()->count();
        $antreanHariIni = ServiceQueue::query()->whereDate('queue_date', today())->count();

        $berita = collect([
            [
                'title' => 'Pelayanan Administrasi Kini Lebih Mudah Diakses Secara Digital',
                'excerpt' => 'Masyarakat dapat melihat persyaratan, mengajukan permohonan, dan memantau status pelayanan melalui sistem yang terintegrasi.',
                'date' => now()->subDays(2),
                'category' => 'Pelayanan',
            ],
            [
                'title' => 'Informasi Jam Pelayanan Kantor Kecamatan Panakkukang',
                'excerpt' => 'Pastikan masyarakat memperhatikan jam pelayanan sebelum datang ke kantor kecamatan untuk mendapatkan layanan langsung.',
                'date' => now()->subDays(6),
                'category' => 'Informasi',
            ],
            [
                'title' => 'Penguatan Pelayanan Publik yang Transparan dan Terukur',
                'excerpt' => 'Pemanfaatan sistem informasi membantu proses pelayanan menjadi lebih tertib, mudah dipantau, dan terdokumentasi.',
                'date' => now()->subDays(10),
                'category' => 'Kegiatan',
            ],
        ]);

        return view('pengunjung.beranda.index', compact(
            'layanan',
            'jumlahLayanan',
            'jumlahSeksi',
            'jumlahPermohonan',
            'antreanHariIni',
            'berita',
        ));
    }
}
