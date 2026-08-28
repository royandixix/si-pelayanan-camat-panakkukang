<?php

namespace App\Filament\Pages;

use App\Enums\ApplicationStatus;
use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use App\Models\Section;
use App\Models\ServiceApplication;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LaporanRekapitulasi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon =
        'heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup =
        'Analisis dan Laporan';

    protected static ?string $navigationLabel =
        'Laporan Rekapitulasi';

    protected static ?string $title =
        'Laporan Rekapitulasi';

    protected static ?string $slug =
        'laporan-rekapitulasi';

    protected static ?int $navigationSort = 2;

    protected string $view =
        'filament.pages.laporan-rekapitulasi';

    public function getHeading(): string
    {
        return 'Laporan Rekapitulasi Pelayanan';
    }

    public function getSubheading(): ?string
    {
        return 'Seluruh data pelayanan dari lima seksi dapat dipantau dan dokumen hasil PDF dapat diunduh oleh Super Admin.';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceApplication::query()
                    ->with([
                        'user',
                        'service.section',
                        'assignedAdmin',
                        'result',
                    ]),
            )
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Masyarakat')
                    ->searchable(),

                TextColumn::make('service.name')
                    ->label('Layanan'),

                TextColumn::make('service.section.name')
                    ->label('Seksi'),

                TextColumn::make('assignedAdmin.name')
                    ->label('Admin Seksi')
                    ->placeholder('Belum diproses'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('completed_at')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('result.original_name')
                    ->label('Hasil PDF')
                    ->placeholder('Belum tersedia'),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->options(
                        fn (): array => Section::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all(),
                    )
                    ->query(function (
                        Builder $query,
                        array $data,
                    ): Builder {
                        $sectionId = $data['value'] ?? null;

                        return $query->when(
                            filled($sectionId),
                            fn (Builder $query): Builder =>
                                $query->whereHas(
                                    'service',
                                    fn (Builder $serviceQuery): Builder =>
                                        $serviceQuery->where(
                                            'section_id',
                                            $sectionId,
                                        ),
                                ),
                        );
                    }),

                SelectFilter::make('service_id')
                    ->label('Layanan')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ApplicationStatus::class),

                Filter::make('periode')
                    ->schema([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal'),

                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->columns(2)
                    ->query(function (
                        Builder $query,
                        array $data,
                    ): Builder {
                        return $query
                            ->when(
                                filled($data['dari'] ?? null),
                                fn (Builder $query): Builder =>
                                    $query->whereDate(
                                        'submitted_at',
                                        '>=',
                                        $data['dari'],
                                    ),
                            )
                            ->when(
                                filled($data['sampai'] ?? null),
                                fn (Builder $query): Builder =>
                                    $query->whereDate(
                                        'submitted_at',
                                        '<=',
                                        $data['sampai'],
                                    ),
                            );
                    }),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn (ServiceApplication $record): string =>
                            ServiceApplicationResource::getUrl(
                                'view',
                                ['record' => $record],
                            ),
                    ),

                Action::make('unduhHasilPdf')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(
                        fn (ServiceApplication $record): string =>
                            route(
                                'admin.permohonan.hasil.download',
                                $record,
                            ),
                    )
                    ->visible(
                        fn (ServiceApplication $record): bool =>
                            filled($record->result?->path),
                    ),
            ])
            ->paginated([
                10,
                25,
                50,
            ])
            ->defaultPaginationPageOption(10);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
