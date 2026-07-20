<?php

namespace App\Filament\Pimpinan\Resources\ServiceApplications\Tables;

use App\Enums\ApplicationStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceApplicationsTable
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
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('user.nik')
                    ->label('NIK')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('service.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->wrap()
                    ->placeholder('-'),

                TextColumn::make('service.section.name')
                    ->label('Seksi')
                    ->searchable()
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
                            'draft'=>'gray',
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
                    ->placeholder('Belum ditugaskan')
                    ->toggleable(),

                TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum selesai')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),
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
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at','desc')
            ->paginationPageOptions([10,25,50,100])
            ->emptyStateHeading('Belum ada permohonan')
            ->emptyStateDescription('Data permohonan pelayanan akan tampil di halaman ini.')
            ->emptyStateIcon('heroicon-o-document-magnifying-glass');
    }
}
