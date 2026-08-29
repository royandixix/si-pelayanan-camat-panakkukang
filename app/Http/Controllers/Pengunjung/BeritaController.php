<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(): View
    {
        $berita = Berita::query()
            ->active()
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->get();

        return view(
            'pengunjung.berita.index',
            compact('berita')
        );
    }

    public function show(Berita $berita): View
    {
        abort_unless(
            $berita->is_active
            && $berita->published_at
            && $berita->published_at->lte(now()),
            404
        );

        $beritaLainnya = Berita::query()
            ->active()
            ->published()
            ->whereKeyNot($berita->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view(
            'pengunjung.berita.show',
            compact(
                'berita',
                'beritaLainnya'
            )
        );
    }
}
