<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Service;
use Illuminate\View\View;

class ProfilKecamatanController extends Controller
{
    public function index(): View
    {
        $jumlahLayanan = Service::query()->where('is_active', true)->count();
        $jumlahSeksi = Section::query()->where('is_active', true)->count();

        return view('pengunjung.profil-kecamatan.index', compact('jumlahLayanan', 'jumlahSeksi'));
    }
}
