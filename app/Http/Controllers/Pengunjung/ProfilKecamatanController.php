<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Section;
use App\Models\Service;
use Illuminate\View\View;

class ProfilKecamatanController extends Controller
{
    public function index(): View
    {
        $jumlahLayanan = Service::query()
            ->where('is_active', true)
            ->count();

        $jumlahSeksi = Section::query()->count();

        $pegawai = Employee::query()
            ->active()
            ->ordered()
            ->limit(6)
            ->get();

        $jumlahPegawai = Employee::query()
            ->active()
            ->count();

        return view('pengunjung.profil-kecamatan.index', compact(
            'jumlahLayanan',
            'jumlahSeksi',
            'pegawai',
            'jumlahPegawai',
        ));
    }
}