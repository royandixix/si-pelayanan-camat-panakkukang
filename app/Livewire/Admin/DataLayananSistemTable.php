<?php

namespace App\Livewire\Admin;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Filament\Resources\ServiceApplications\ServiceApplicationResource;
use App\Models\Section;
use App\Models\ServiceApplication;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class DataLayananSistemTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Data Pelayanan Sistem')
            ->description(
                'Pelayanan yang telah selesai diproses oleh Admin Seksi dan memiliki dokumen hasil.'
            )
            ->query(
                ServiceApplication::query()
                    ->with([
                        'user',
                        'service.section',
                        'assignedAdmin',
                        'result.uploader',
                    ])
                    ->whereIn(
                        'status',
                        [
                            ApplicationStatus::COMPLETED->value,
                            ApplicationStatus::COLLECTED->value,
                        ],
                    )
                    ->whereHas('result')
                    ->whereHas(
                        'result.uploader',
                        fn (Builder $query): Builder =>
                            $query->whereIn(
                                'role',
                                UserRole::adminSeksiValues(),
                            ),
                    )
            )
            ->defaultSort('completed_at', 'desc')
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor Registrasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Masyarakat')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('result.uploader.name')
                    ->label('Admin Seksi')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->options(
                        fn (): array =>
                            Section::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all()
                    )
                    ->query(
                        function (
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
                        },
                    ),

                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(
                        fn (ServiceApplication $record): string =>
                            ServiceApplicationResource::getUrl(
                                'view',
                                [
                                    'record' => $record,
                                ],
                            ),
                    ),

                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(
                        fn (ServiceApplication $record): string =>
                            route(
                                'admin.permohonan.hasil.download',
                                $record,
                            ),
                    )
                    ->openUrlInNewTab()
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
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Belum ada pelayanan selesai')
            ->emptyStateDescription(
                'Data akan masuk setelah Admin Seksi menyelesaikan pelayanan.'
            )
            ->emptyStateIcon('heroicon-o-document-check');
    }

    public function render()
    {
        return view(
            'livewire.admin.data-layanan-sistem-table'
        );
    }
}
