<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = $this->dataBerita();

        return view('pengunjung.berita.index', compact('berita'));
    }

    private function dataBerita(): Collection
    {
        return collect([
            [
                'title' => 'Pelayanan Administrasi Kini Lebih Mudah Diakses Secara Digital',
                'excerpt' => 'Sistem informasi pelayanan masyarakat membantu proses pengajuan, unggah dokumen, dan pemantauan status pelayanan dalam satu tempat.',
                'date' => now()->subDays(2),
                'category' => 'Pelayanan',
                'image' => 'img/pengunjung/gallery-digital.svg',
            ],
            [
                'title' => 'Informasi Jam Pelayanan Kantor Kecamatan Panakkukang',
                'excerpt' => 'Masyarakat diimbau memperhatikan jam pelayanan dan menyiapkan dokumen persyaratan sebelum datang ke kantor kecamatan.',
                'date' => now()->subDays(5),
                'category' => 'Informasi',
                'image' => 'img/pengunjung/about-office.svg',
            ],
            [
                'title' => 'Peningkatan Kualitas Pelayanan Publik Berbasis Teknologi',
                'excerpt' => 'Digitalisasi pelayanan mendukung proses administrasi yang lebih tertib, cepat, transparan, dan mudah dipantau oleh masyarakat.',
                'date' => now()->subDays(8),
                'category' => 'Transformasi Digital',
                'image' => 'img/pengunjung/gallery-data.svg',
            ],
            [
                'title' => 'Koordinasi Pelayanan untuk Meningkatkan Kepuasan Masyarakat',
                'excerpt' => 'Koordinasi antarbagian menjadi salah satu langkah untuk memastikan pelayanan berjalan sesuai prosedur dan kebutuhan masyarakat.',
                'date' => now()->subDays(12),
                'category' => 'Kegiatan',
                'image' => 'img/pengunjung/gallery-meeting.svg',
            ],
            [
                'title' => 'Kemudahan Memantau Status Permohonan Melalui Dashboard',
                'excerpt' => 'Pemohon dapat mengikuti perkembangan permohonan dan menerima informasi terkait revisi maupun penyelesaian layanan.',
                'date' => now()->subDays(16),
                'category' => 'Pelayanan',
                'image' => 'img/pengunjung/hero-monitoring.svg',
            ],
            [
                'title' => 'Pelayanan yang Lebih Dekat dengan Masyarakat Panakkukang',
                'excerpt' => 'Kecamatan Panakkukang terus mendorong pelayanan yang mudah dijangkau serta memberikan informasi yang jelas kepada masyarakat.',
                'date' => now()->subDays(20),
                'category' => 'Masyarakat',
                'image' => 'img/pengunjung/gallery-community.svg',
            ],
        ]);
    }
}
