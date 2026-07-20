<?php

namespace App\Filament\Pimpinan\Resources\Sections\Schemas;

use App\Enums\ApplicationStatus;
use App\Models\Section as SectionModel;
use App\Models\ServiceApplication;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Seksi')
                    ->icon('heroicon-o-building-office-2')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Kode Seksi')
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('name')
                            ->label('Nama Seksi')
                            ->weight('bold')
                            ->columnSpan(2),

                        TextEntry::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Belum ada deskripsi.')
                            ->columnSpanFull(),

                        TextEntry::make('employee_count')
                            ->label('Jumlah Pegawai')
                            ->numeric(),

                        TextEntry::make('daily_queue_quota')
                            ->label('Kuota Antrean Harian')
                            ->numeric()
                            ->placeholder('Tidak menggunakan antrean'),

                        IconEntry::make('is_active')
                            ->label('Status Aktif')
                            ->boolean(),
                    ]),

                Section::make('Ringkasan Kinerja Pelayanan')
                    ->icon('heroicon-o-chart-pie')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('services_total')
                            ->label('Jumlah Jenis Layanan')
                            ->state(
                                fn (SectionModel $record): int =>
                                    $record->services()->count(),
                            )
                            ->numeric(),

                        TextEntry::make('applications_total')
                            ->label('Total Permohonan')
                            ->state(
                                fn (SectionModel $record): int =>
                                    self::applicationQuery($record)->count(),
                            )
                            ->numeric()
                            ->color('primary')
                            ->weight('bold'),

                        TextEntry::make('applications_waiting')
                            ->label('Menunggu Verifikasi')
                            ->state(
                                fn (SectionModel $record): int =>
                                    self::applicationQuery($record)
                                        ->whereIn('status', [
                                            ApplicationStatus::SUBMITTED->value,
                                            ApplicationStatus::VERIFICATION->value,
                                            ApplicationStatus::REVISION->value,
                                        ])
                                        ->count(),
                            )
                            ->numeric()
                            ->color('warning'),

                        TextEntry::make('applications_processing')
                            ->label('Sedang Diproses')
                            ->state(
                                fn (SectionModel $record): int =>
                                    self::applicationQuery($record)
                                        ->whereIn('status', [
                                            ApplicationStatus::APPROVED->value,
                                            ApplicationStatus::PROCESSING->value,
                                        ])
                                        ->count(),
                            )
                            ->numeric()
                            ->color('info'),

                        TextEntry::make('applications_completed')
                            ->label('Permohonan Selesai')
                            ->state(
                                fn (SectionModel $record): int =>
                                    self::applicationQuery($record)
                                        ->whereIn('status', [
                                            ApplicationStatus::COMPLETED->value,
                                            ApplicationStatus::COLLECTED->value,
                                        ])
                                        ->count(),
                            )
                            ->numeric()
                            ->color('success'),

                        TextEntry::make('queues_total')
                            ->label('Total Pengambilan Antrean')
                            ->state(
                                fn (SectionModel $record): int =>
                                    $record->queues()->count(),
                            )
                            ->numeric(),

                        TextEntry::make('latest_kmeans_category')
                            ->label('Kategori K-Means Terbaru')
                            ->state(
                                fn (SectionModel $record): string =>
                                    $record->kMeansResults()
                                        ->latest('k_means_run_id')
                                        ->value('workload_category') ?? '-',
                            )
                            ->badge(),

                        TextEntry::make('latest_recommendation')
                            ->label('Rekomendasi Terbaru')
                            ->state(
                                fn (SectionModel $record): string =>
                                    $record->kMeansResults()
                                        ->latest('k_means_run_id')
                                        ->value('recommendation')
                                        ?? 'Belum ada rekomendasi K-Means.',
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function applicationQuery(
        SectionModel $section,
    ): \Illuminate\Database\Eloquent\Builder {
        return ServiceApplication::query()
            ->whereHas(
                'service',
                fn ($query) =>
                    $query->where('section_id', $section->id),
            );
    }
}
