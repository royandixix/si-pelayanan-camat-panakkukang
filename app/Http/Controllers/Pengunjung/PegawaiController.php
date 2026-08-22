<?php

namespace App\Http\Controllers\Pengunjung;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function index(Request $request): View
    {
        $unit = trim((string) $request->query('unit'));

        $pegawai = Employee::query()
            ->active()
            ->when(
                $unit !== '',
                fn ($query) => $query->where('work_unit', $unit),
            )
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        $unitKerja = Employee::query()
            ->active()
            ->whereNotNull('work_unit')
            ->where('work_unit', '!=', '')
            ->distinct()
            ->orderBy('work_unit')
            ->pluck('work_unit');

        return view('pengunjung.pegawai.index', compact(
            'pegawai',
            'unitKerja',
            'unit',
        ));
    }
}