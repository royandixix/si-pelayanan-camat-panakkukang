<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AntreanController extends Controller
{

    public function store(Service $layanan): RedirectResponse
    {
        $this->pastikanMasyarakat();

        abort_unless(
            $layanan->is_active
            && $layanan->queue_enabled,
            404,
        );

        $layanan->loadMissing('section');

        $existing = ServiceQueue::query()
            ->where('user_id', auth()->id())
            ->where('service_id', $layanan->id)
            ->whereDate('queue_date', '>=', today())
            ->whereIn('status', [
                'waiting',
                'called',
                'serving',
            ])
            ->orderBy('queue_date')
            ->orderBy('sequence')
            ->first();

        if ($existing) {
            return redirect()->route(
                'masyarakat.antrean.show',
                $existing,
            );
        }

        $antrean = DB::transaction(
            function () use ($layanan): ServiceQueue {
                $quota = max(
                    1,
                    (int) (
                        $layanan->section?->daily_queue_quota
                        ?? 30
                    ),
                );

                $tanggal = today();

                for (
                    $percobaan = 0;
                    $percobaan < 120;
                    $percobaan++
                ) {
                    while ($tanggal->isWeekend()) {
                        $tanggal->addDay();
                    }

                    $jumlah = ServiceQueue::query()
                        ->where(
                            'section_id',
                            $layanan->section_id,
                        )
                        ->whereDate(
                            'queue_date',
                            $tanggal,
                        )
                        ->lockForUpdate()
                        ->count();

                    $urutanTerakhir =
                        (int) ServiceQueue::query()
                            ->where(
                                'section_id',
                                $layanan->section_id,
                            )
                            ->whereDate(
                                'queue_date',
                                $tanggal,
                            )
                            ->lockForUpdate()
                            ->max('sequence');

                    if (
                        $jumlah < $quota
                        && $urutanTerakhir < $quota
                    ) {
                        $urutan =
                            $urutanTerakhir + 1;

                        return ServiceQueue::query()
                            ->create([
                                'application_id' => null,
                                'user_id' => auth()->id(),
                                'section_id' =>
                                    $layanan->section_id,
                                'service_id' =>
                                    $layanan->id,
                                'queue_date' =>
                                    $tanggal
                                        ->toDateString(),
                                'prefix' => 'A',
                                'sequence' => $urutan,
                                'queue_number' =>
                                    'A-'
                                    . str_pad(
                                        (string) $urutan,
                                        3,
                                        '0',
                                        STR_PAD_LEFT,
                                    ),
                                'status' => 'waiting',
                                'registered_at' => now(),
                            ]);
                    }

                    $tanggal->addDay();
                }

                abort(
                    422,
                    'Jadwal antrean tidak tersedia.',
                );
            },
        );

        return redirect()->route(
            'masyarakat.antrean.show',
            $antrean,
        );
    }

    public function index(Request $request): View
    {
        $this->pastikanMasyarakat();

        $status = trim($request->string('status')->toString());
        $statusPilihan = $this->statusPilihan();

        if (!array_key_exists($status, $statusPilihan)) {
            $status = '';
        }

        $dasarAntrean = ServiceQueue::query()
            ->where('user_id', auth()->id());

        $totalAntrean = (clone $dasarAntrean)->count();

        $menunggu = (clone $dasarAntrean)
            ->where('status', 'waiting')
            ->count();

        $dipanggil = (clone $dasarAntrean)
            ->where('status', 'called')
            ->count();

        $sedangDilayani = (clone $dasarAntrean)
            ->whereIn('status', ['serving', 'in_service'])
            ->count();

        $selesai = (clone $dasarAntrean)
            ->where('status', 'served')
            ->count();

        $antreanAktif = (clone $dasarAntrean)
            ->with(['service', 'section', 'application'])
            ->whereNotIn('status', ['served', 'cancelled'])
            ->where(function (Builder $query): void {
                $query
                    ->whereDate('queue_date', '>=', today())
                    ->orWhereIn('status', ['called', 'serving', 'in_service']);
            })
            ->orderByRaw("
                CASE
                    WHEN status IN ('called', 'serving', 'in_service') THEN 0
                    ELSE 1
                END
            ")
            ->orderBy('queue_date')
            ->orderBy('sequence')
            ->first();

        $antrean = (clone $dasarAntrean)
            ->with(['service', 'section', 'application'])
            ->when($status !== '', function (Builder $query) use ($status): void {
                if ($status === 'serving') {
                    $query->whereIn('status', ['serving', 'in_service']);

                    return;
                }

                $query->where('status', $status);
            })
            ->orderByDesc('queue_date')
            ->orderByDesc('sequence')
            ->paginate(10)
            ->withQueryString();

        return view('masyarakat.antrean.index', compact(
            'antrean',
            'antreanAktif',
            'totalAntrean',
            'menunggu',
            'dipanggil',
            'sedangDilayani',
            'selesai',
            'status',
            'statusPilihan',
        ));
    }

    public function show(ServiceQueue $antrean): View
    {
        $this->pastikanMasyarakat();

        abort_unless(
            (int) $antrean->user_id === (int) auth()->id(),
            403,
        );

        $antrean->load([
            'application.service',
            'service',
            'section',
        ]);

        $statusValue = $antrean->status instanceof \BackedEnum
            ? $antrean->status->value
            : (string) $antrean->status;

        $jumlahDiDepan = 0;

        if ($statusValue === 'waiting') {
            $jumlahDiDepan = ServiceQueue::query()
                ->where('section_id', $antrean->section_id)
                ->whereDate('queue_date', $antrean->queue_date)
                ->where('sequence', '<', $antrean->sequence)
                ->whereIn('status', [
                    'waiting',
                    'called',
                    'serving',
                    'in_service',
                ])
                ->count();
        }

        $antreanSedangDilayani = ServiceQueue::query()
            ->where('section_id', $antrean->section_id)
            ->whereDate('queue_date', $antrean->queue_date)
            ->whereIn('status', [
                'called',
                'serving',
                'in_service',
            ])
            ->orderBy('sequence')
            ->first();

        return view('masyarakat.antrean.show', compact(
            'antrean',
            'statusValue',
            'jumlahDiDepan',
            'antreanSedangDilayani',
        ));
    }

    private function statusPilihan(): array
    {
        return [
            'waiting' => 'Menunggu',
            'called' => 'Dipanggil',
            'serving' => 'Sedang Dilayani',
            'served' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    }

    private function pastikanMasyarakat(): void
    {
        $pengguna = auth()->user();

        $role = $pengguna?->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna?->role);

        abort_unless(
            $pengguna
            && $role === UserRole::MASYARAKAT
            && $pengguna->is_active,
            403,
        );
    }
}