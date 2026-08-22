<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::query()
            ->with(['section', 'requirements'])
            ->where('is_active', true);

        if ($request->filled('seksi')) {
            $query->whereHas('section', function ($sectionQuery) use ($request): void {
                $sectionQuery->where('code', $request->string('seksi')->toString());
            });
        }

        if ($request->filled('cari')) {
            $search = $request->string('cari')->toString();
            $query->where(function ($serviceQuery) use ($search): void {
                $serviceQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $layanan = $query->orderBy('name')->get();
        $seksi = Section::query()->where('is_active', true)->orderBy('name')->get();

        return view('pengunjung.layanan.index', compact('layanan', 'seksi'));
    }
}
