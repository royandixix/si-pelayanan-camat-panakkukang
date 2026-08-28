<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(): View
    {
        $galeri = Gallery::query()
            ->active()
            ->ordered()
            ->get();

        return view(
            'pengunjung.galeri.index',
            compact('galeri')
        );
    }

    public function show(Gallery $gallery): View
    {
        abort_unless($gallery->is_active, 404);

        $galeriLainnya = Gallery::query()
            ->active()
            ->whereKeyNot($gallery->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view(
            'pengunjung.galeri.show',
            compact(
                'gallery',
                'galeriLainnya'
            )
        );
    }
}
