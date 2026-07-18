<?php

namespace App\Filament\Pimpinan\Resources\ServiceReports\Tables;

use App\Enums\ApplicationStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor Permohonan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('semibold')
                    ->color('primary'),

                TextColumn::make('user.name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('user.nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('service.section.name')
                    ->label('Seksi')
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function(mixed $state): string {
                        if($state instanceof ApplicationStatus){
                            return (string)$state->getLabel();
                        }

                        return str((string)$state)
                            ->replace('_',' ')
                            ->title()
                            ->toString();
                    })
                    ->color(function(mixed $state): string|array|null {
                        if($state instanceof ApplicationStatus){
                            return $state->getColor();
                        }

                        return match((string)$state){
                            'submitted'=>'info',
                            'verification'=>'warning',
                            'revision'=>'warning',
                            'approved'=>'success',
                            'processing'=>'primary',
                            'completed'=>'success',
                            'collected'=>'success',
                            'rejected'=>'danger',
                            default=>'gray',
                        };
                    }),

                TextColumn::make('assignedAdmin.name')
                    ->label('Petugas')
                    ->placeholder('-'),

                TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Permohonan')
                    ->options(
                        collect(ApplicationStatus::cases())
                            ->mapWithKeys(
                                fn(ApplicationStatus $status): array=>[
                                    $status->value=>(string)$status->getLabel(),
                                ],
                            )
                            ->all(),
                    ),

                SelectFilter::make('service_id')
                    ->label('Jenis Layanan')
                    ->relationship('service','name')
                    ->searchable()
                    ->preload(),

                Filter::make('periode')
                    ->label('Periode Pengajuan')
                    ->schema([
                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->native(false),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->native(false),
                    ])
                    ->query(function(Builder $query,array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_mulai']??null,
                                fn(Builder $query,string $date): Builder=>
                                    $query->whereDate('submitted_at','>=',$date),
                            )
                            ->when(
                                $data['tanggal_selesai']??null,
                                fn(Builder $query,string $date): Builder=>
                                    $query->whereDate('submitted_at','<=',$date),
                            );
                    }),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('submitted_at','desc')
            ->paginationPageOptions([10,25,50,100])
            ->emptyStateHeading('Belum ada laporan pelayanan')
            ->emptyStateDescription('Data laporan akan tampil setelah terdapat permohonan pelayanan.')
            ->emptyStateIcon('heroicon-o-document-chart-bar');
    }
}
