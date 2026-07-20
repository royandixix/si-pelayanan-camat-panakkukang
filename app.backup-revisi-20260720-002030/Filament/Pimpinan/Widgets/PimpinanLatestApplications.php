<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Filament\Pimpinan\Resources\ServiceApplications\ServiceApplicationResource;
use App\Models\ServiceApplication;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class PimpinanLatestApplications extends TableWidget
{
    protected static ?int $sort=5;

    protected int|string|array $columnSpan='full';

    protected static ?string $heading='Permohonan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ServiceApplication::query()
                    ->with([
                        'user',
                        'service',
                        'service.section',
                        'assignedAdmin',
                    ])
                    ->latest('created_at')
                    ->limit(10),
            )
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Nomor Permohonan')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold')
                    ->color('primary'),

                TextColumn::make('user.name')
                    ->label('Nama Pemohon')
                    ->searchable()
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

                TextColumn::make('assignedAdmin.name')
                    ->label('Petugas')
                    ->placeholder('Belum ditugaskan')
                    ->toggleable(),

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

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(
                        fn(ServiceApplication $record): string=>
                            ServiceApplicationResource::getUrl(
                                'view',
                                ['record'=>$record],
                            ),
                    ),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada permohonan')
            ->emptyStateDescription('Permohonan terbaru akan tampil di sini.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public static function canView(): bool
    {
        $user=Auth::user();

        return $user instanceof User
            && $user->role===UserRole::PIMPINAN;
    }
}
