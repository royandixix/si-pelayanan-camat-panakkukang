<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = $this->penggunaMasyarakat($request);

        $kataKunci = trim(
            (string) $request->query(
                'cari',
                $request->query(
                    'q',
                    $request->query('search', ''),
                ),
            ),
        );

        $nilaiSeksi = $request->query(
            'seksi',
            $request->query(
                'section_id',
                $request->query('section'),
            ),
        );

        $seksiDipilih = is_numeric($nilaiSeksi)
            && (int) $nilaiSeksi > 0
                ? (int) $nilaiSeksi
                : null;

        $daftarSeksi = Section::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $layanan = Service::query()
            ->with('section')
            ->withCount('requirements')
            ->where('is_active', true)
            ->when(
                $seksiDipilih,
                function (
                    Builder $query,
                    int $sectionId,
                ): void {
                    $query->where(
                        'section_id',
                        $sectionId,
                    );
                },
            )
            ->when(
                $kataKunci !== '',
                function (
                    Builder $query,
                ) use (
                    $kataKunci,
                ): void {
                    $query->where(function (
                        Builder $pencarian,
                    ) use (
                        $kataKunci,
                    ): void {
                        $pencarian
                            ->where(
                                'name',
                                'like',
                                '%' . $kataKunci . '%',
                            )
                            ->orWhere(
                                'code',
                                'like',
                                '%' . $kataKunci . '%',
                            )
                            ->orWhere(
                                'description',
                                'like',
                                '%' . $kataKunci . '%',
                            )
                            ->orWhereHas(
                                'section',
                                function (
                                    Builder $seksi,
                                ) use (
                                    $kataKunci,
                                ): void {
                                    $seksi
                                        ->where(
                                            'name',
                                            'like',
                                            '%' . $kataKunci . '%',
                                        )
                                        ->orWhere(
                                            'code',
                                            'like',
                                            '%' . $kataKunci . '%',
                                        );
                                },
                            );
                    });
                },
            )
            ->orderBy('section_id')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('masyarakat.layanan.index', [
            'pengguna' => $pengguna,
            'layanan' => $layanan,
            'daftarLayanan' => $layanan,
            'services' => $layanan,
            'daftarSeksi' => $daftarSeksi,
            'kataKunci' => $kataKunci,
            'pencarian' => $kataKunci,
            'seksiDipilih' => $seksiDipilih,
            'sectionDipilih' => $seksiDipilih,
            'filterSeksi' => $seksiDipilih,
        ]);
    }

    public function show(
        Request $request,
        Service $layanan,
    ): View {
        $pengguna = $this->penggunaMasyarakat($request);

        abort_unless(
            $layanan->is_active,
            404,
        );

        $layanan->load([
            'section',
            'requirements' => function ($query): void {
                $query
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
        ]);

        return view('masyarakat.layanan.show', [
            'pengguna' => $pengguna,
            'layanan' => $layanan,
            'service' => $layanan,
            'persyaratan' => $layanan->requirements,
        ]);
    }

    private function penggunaMasyarakat(
        Request $request,
    ): User {
        $pengguna = $request->user();

        $role = $pengguna?->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna?->role);

        abort_unless(
            $pengguna instanceof User
            && $role === UserRole::MASYARAKAT
            && $pengguna->is_active,
            403,
        );

        return $pengguna;
    }
}