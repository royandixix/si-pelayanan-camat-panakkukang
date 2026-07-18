<?php

namespace App\Http\Controllers\Pimpinan;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Service;
use App\Models\ServiceApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ServiceReportPreviewController extends Controller
{
    public function previewCsv(Request $request): View
    {
        $this->authorizePimpinan();

        $filters = $this->validatedFilters($request);
        $records = $this->reportQuery($filters)
            ->orderByDesc('submitted_at')
            ->get();

        return view('pimpinan.reports.service-preview', [
            'records' => $records,
            'summary' => $this->summary($records),
            'filterDescription' => $this->filterDescription($filters),
        ]);
    }

    public function previewPdf(Request $request): Response
    {
        $this->authorizePimpinan();

        $filters = $this->validatedFilters($request);
        $records = $this->reportQuery($filters)
            ->orderByDesc('submitted_at')
            ->get();

        return $this->makePdf($records, $filters)
            ->stream(
                'pratinjau-laporan-pelayanan-'
                . now()->format('Y-m-d-His')
                . '.pdf',
            );
    }

    public function downloadCsv(Request $request): StreamedResponse
    {
        $this->authorizePimpinan();

        $filters = $this->validatedFilters($request);

        $filename = 'laporan-pelayanan-'
            . now()->format('Y-m-d-His')
            . '.csv';

        return response()->streamDownload(
            function () use ($filters): void {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'No',
                    'Nomor Permohonan',
                    'Nama Pemohon',
                    'NIK',
                    'Jenis Layanan',
                    'Seksi',
                    'Status',
                    'Petugas',
                    'Tanggal Pengajuan',
                    'Tanggal Selesai',
                ]);

                $number = 1;

                $this->reportQuery($filters)
                    ->orderBy('id')
                    ->chunkById(
                        500,
                        function ($records) use (
                            $handle,
                            &$number,
                        ): void {
                            foreach ($records as $record) {
                                fputcsv($handle, [
                                    $number++,
                                    $record->registration_number ?? '-',
                                    $record->user?->name ?? '-',
                                    $record->user?->nik ?? '-',
                                    $record->service?->name ?? '-',
                                    $record->service?->section?->name ?? '-',
                                    $this->statusLabel($record->status),
                                    $record->assignedAdmin?->name
                                        ?? 'Belum ditugaskan',
                                    $record->submitted_at?->format(
                                        'd-m-Y H:i',
                                    ) ?? '-',
                                    $record->completed_at?->format(
                                        'd-m-Y H:i',
                                    ) ?? '-',
                                ]);
                            }
                        },
                    );

                fclose($handle);
            },
            $filename,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ],
        );
    }

    public function downloadPdf(Request $request): Response
    {
        $this->authorizePimpinan();

        $filters = $this->validatedFilters($request);
        $records = $this->reportQuery($filters)
            ->orderByDesc('submitted_at')
            ->get();

        return $this->makePdf($records, $filters)
            ->download(
                'laporan-pelayanan-'
                . now()->format('Y-m-d-His')
                . '.pdf',
            );
    }

    private function makePdf(
        Collection $records,
        array $filters,
    ) {
        return Pdf::loadView(
            'pimpinan.reports.service-pdf',
            [
                'records' => $records,
                'summary' => $this->summary($records),
                'filterDescription' =>
                    $this->filterDescription($filters),
            ],
        )->setPaper('a4', 'landscape');
    }

    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'tanggal_mulai' => [
                'nullable',
                'date',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'section_id' => [
                'nullable',
                'integer',
                'exists:sections,id',
            ],
            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id',
            ],
            'status' => [
                'nullable',
                Rule::enum(ApplicationStatus::class),
            ],
        ]);
    }

    private function reportQuery(array $filters): Builder
    {
        return ServiceApplication::query()
            ->with([
                'user',
                'service',
                'service.section',
                'assignedAdmin',
            ])
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn (
                    Builder $query,
                    string $date,
                ): Builder => $query->whereDate(
                    'submitted_at',
                    '>=',
                    $date,
                ),
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn (
                    Builder $query,
                    string $date,
                ): Builder => $query->whereDate(
                    'submitted_at',
                    '<=',
                    $date,
                ),
            )
            ->when(
                $filters['section_id'] ?? null,
                fn (
                    Builder $query,
                    mixed $sectionId,
                ): Builder => $query->whereHas(
                    'service',
                    fn (
                        Builder $serviceQuery,
                    ): Builder => $serviceQuery->where(
                        'section_id',
                        $sectionId,
                    ),
                ),
            )
            ->when(
                $filters['service_id'] ?? null,
                fn (
                    Builder $query,
                    mixed $serviceId,
                ): Builder => $query->where(
                    'service_id',
                    $serviceId,
                ),
            )
            ->when(
                $filters['status'] ?? null,
                fn (
                    Builder $query,
                    string $status,
                ): Builder => $query->where(
                    'status',
                    $status,
                ),
            );
    }

    private function summary(Collection $records): array
    {
        return [
            'total' => $records->count(),

            'selesai' => $records
                ->filter(
                    fn (
                        ServiceApplication $record,
                    ): bool => in_array(
                        $this->statusValue($record->status),
                        [
                            ApplicationStatus::COMPLETED->value,
                            ApplicationStatus::COLLECTED->value,
                        ],
                        true,
                    ),
                )
                ->count(),

            'diproses' => $records
                ->filter(
                    fn (
                        ServiceApplication $record,
                    ): bool => in_array(
                        $this->statusValue($record->status),
                        [
                            ApplicationStatus::APPROVED->value,
                            ApplicationStatus::PROCESSING->value,
                        ],
                        true,
                    ),
                )
                ->count(),

            'ditolak' => $records
                ->filter(
                    fn (
                        ServiceApplication $record,
                    ): bool => $this->statusValue(
                        $record->status,
                    ) === ApplicationStatus::REJECTED->value,
                )
                ->count(),
        ];
    }

    private function filterDescription(array $filters): string
    {
        $description = [];

        if ($filters['tanggal_mulai'] ?? null) {
            $description[] = 'Mulai '
                . date(
                    'd-m-Y',
                    strtotime($filters['tanggal_mulai']),
                );
        }

        if ($filters['tanggal_selesai'] ?? null) {
            $description[] = 'Sampai '
                . date(
                    'd-m-Y',
                    strtotime($filters['tanggal_selesai']),
                );
        }

        if ($filters['section_id'] ?? null) {
            $description[] = 'Seksi: '
                . (
                    Section::query()
                        ->find($filters['section_id'])
                        ?->name
                    ?? '-'
                );
        }

        if ($filters['service_id'] ?? null) {
            $description[] = 'Layanan: '
                . (
                    Service::query()
                        ->find($filters['service_id'])
                        ?->name
                    ?? '-'
                );
        }

        if ($filters['status'] ?? null) {
            $description[] = 'Status: '
                . $this->statusLabel($filters['status']);
        }

        return $description === []
            ? 'Semua data pelayanan'
            : implode(' | ', $description);
    }

    private function statusValue(mixed $status): string
    {
        if ($status instanceof \BackedEnum) {
            return (string) $status->value;
        }

        return (string) $status;
    }

    private function statusLabel(mixed $status): string
    {
        if ($status instanceof ApplicationStatus) {
            return (string) $status->getLabel();
        }

        $case = ApplicationStatus::tryFrom(
            $this->statusValue($status),
        );

        if ($case) {
            return (string) $case->getLabel();
        }

        return str($this->statusValue($status))
            ->replace('_', ' ')
            ->title()
            ->toString();
    }

    private function authorizePimpinan(): void
    {
        $user = auth()->user();

        abort_unless(
            $user
            && $user->is_active
            && $user->role === UserRole::PIMPINAN,
            403,
        );
    }
}
