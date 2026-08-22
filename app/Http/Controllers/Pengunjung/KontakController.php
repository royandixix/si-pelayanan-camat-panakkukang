<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class KontakController extends Controller
{
    public function index(): View
    {
        $kontak = [
            'alamat' => 'Kecamatan Panakkukang, Kota Makassar, Sulawesi Selatan',
            'telepon' => 'Informasi tersedia di kantor kecamatan',
            'email' => 'Informasi tersedia di kantor kecamatan',
            'senin_kamis' => '08.00 - 16.00 WITA',
            'jumat' => '08.00 - 11.30 WITA',
        ];

        return view('pengunjung.kontak.index', compact('kontak'));
    }
}
