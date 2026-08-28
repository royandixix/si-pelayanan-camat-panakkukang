<?php

namespace App\Livewire\Admin;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;

use App\Models\ResearchDatasetRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component;

class DataHistorisLayananTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Data Historis Pelayanan Tahun 2025')
            ->description(
                'Sebanyak 480 data pelayanan riil tahun 2025 yang berstatus valid.'
            )
            ->query(
                ResearchDatasetRecord::query()
                    ->with([
                        'section',
                        'service',
                    ])
                    ->where(
                        'validation_status',
                        'valid',
                    )
                    ->whereYear(
                        'record_date',
                        2025,
                    )
            )
            ->defaultSort('record_date', 'desc')
            ->columns([
                TextColumn::make('source_row_no')
                    ->label('No. Data')
                    ->sortable(),

                TextColumn::make('record_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('subject_name')
                    ->label('Nama')
                    ->placeholder('-')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->placeholder('-')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('dataset_name')
                    ->label('Dataset')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship(
                        'section',
                        'name',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship(
                        'service',
                        'name',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('dataset_name')
                    ->label('Jenis Data')
                    ->options([
                        'Pewarisan' =>
                            'Keterangan Ahli Waris',

                        'Izin Meneliti' =>
                            'Izin Meneliti',

                        'Rekomendasi Kegiatan' =>
                            'Rekomendasi Kegiatan',
                    ]),
            ])
            ->paginated([
                10,
                25,
                50,
                100,
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading(
                'Data historis tidak ditemukan'
            )
            ->emptyStateIcon(
                'heroicon-o-archive-box'
            );
    }

    public function render()
    {
        return view(
            'livewire.admin.data-historis-layanan-table'
        );
    }
}
