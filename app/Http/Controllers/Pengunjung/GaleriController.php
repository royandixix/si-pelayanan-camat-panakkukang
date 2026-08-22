<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeri = collect([
            [
                'title' => 'Pelayanan Masyarakat',
                'description' => 'Aktivitas pelayanan administrasi masyarakat di Kecamatan Panakkukang.',
                'image' => 'img/pengunjung/gallery-service.svg',
                'category' => 'Pelayanan',
            ],
            [
                'title' => 'Koordinasi Pemerintahan',
                'description' => 'Koordinasi internal dalam mendukung kualitas pelayanan kepada masyarakat.',
                'image' => 'img/pengunjung/gallery-meeting.svg',
                'category' => 'Kegiatan',
            ],
            [
                'title' => 'Transformasi Pelayanan Digital',
                'description' => 'Pemanfaatan teknologi informasi untuk mendukung pelayanan publik yang terintegrasi.',
                'image' => 'img/pengunjung/gallery-digital.svg',
                'category' => 'Digitalisasi',
            ],
            [
                'title' => 'Kegiatan Bersama Masyarakat',
                'description' => 'Partisipasi masyarakat dalam kegiatan di wilayah Kecamatan Panakkukang.',
                'image' => 'img/pengunjung/gallery-community.svg',
                'category' => 'Masyarakat',
            ],
            [
                'title' => 'Monitoring Data Pelayanan',
                'description' => 'Pemantauan data membantu evaluasi pelayanan dan pengambilan keputusan.',
                'image' => 'img/pengunjung/gallery-data.svg',
                'category' => 'Monitoring',
            ],
            [
                'title' => 'Kantor Kecamatan Panakkukang',
                'description' => 'Pelayanan publik yang mudah diakses dan berorientasi pada kebutuhan masyarakat.',
                'image' => 'img/pengunjung/about-office.svg',
                'category' => 'Kecamatan',
            ],
        ]);

        return view('pengunjung.galeri.index', compact('galeri'));
    }
}
