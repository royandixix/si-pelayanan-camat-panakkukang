<?php

namespace App\Filament\Pimpinan\Widgets;

use App\Enums\UserRole;
use App\Filament\Pimpinan\Resources\KMeansResults\KMeansResultResource;
use App\Models\KMeansResult;
use App\Models\KMeansRun;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PimpinanLatestKMeansResults extends TableWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading =
        'Rekomendasi Distribusi Pegawai Berdasarkan K-Means';

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->latestResultQuery())
            ->columns([
                TextColumn::make('rank')
                    ->label('Peringkat')
                    ->numeric()
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->wrap()
                    ->weight('semibold'),

                TextColumn::make('total_volume')
                    ->label('Total Volume')
                    ->numeric()
                    ->alignCenter()
                    ->color('primary')
                    ->weight('bold'),

                TextColumn::make('employee_count')
                    ->label('Pegawai Saat Ini')
                    ->numeric()
                    ->alignCenter(),

                TextColumn::make('workload_category')
                    ->label('Kategori Beban')
                    ->badge()
                    ->formatStateUsing(
                        fn (?string $state): string =>
                            $this->workloadLabel($state),
                    )
                    ->color(
                        fn (?string $state): string =>
                            $this->workloadColor($state),
                    ),

                TextColumn::make('recommended_employee_change')
                    ->label('Perubahan Pegawai')
                    ->formatStateUsing(function (mixed $state): string {
                        $value = (int) $state;

                        if ($value > 0) {
                            return '+' . $value;
                        }

                        if ($value < 0) {
                            return (string) $value;
                        }

                        return 'Tetap';
                    })
                    ->badge()
                    ->color(function (mixed $state): string {
                        $value = (int) $state;

                        return match (true) {
                            $value > 0 => 'success',
                            $value < 0 => 'warning',
                            default => 'gray',
                        };
                    }),

                TextColumn::make('recommendation')
                    ->label('Rekomendasi')
                    ->wrap()
                    ->limit(110),
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(
                        fn (KMeansResult $record): string =>
                            KMeansResultResource::getUrl(
                                'view',
                                ['record' => $record],
                            ),
                    ),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada rekomendasi K-Means')
            ->emptyStateDescription(
                'Rekomendasi akan tampil setelah proses K-Means selesai dijalankan.',
            )
            ->emptyStateIcon('heroicon-o-chart-bar-square');
    }

    private function latestResultQuery(): Builder
    {
        $latestRunId = KMeansRun::query()
            ->whereHas('results')
            ->orderByDesc('executed_at')
            ->orderByDesc('id')
            ->value('id');

        $query = KMeansResult::query()
            ->with([
                'section',
                'run',
            ])
            ->orderBy('rank');

        if (! $latestRunId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('k_means_run_id', $latestRunId);
    }

    private function workloadLabel(?string $state): string
    {
        $value = Str::lower(trim((string) $state));

        return match (true) {
            Str::contains($value, ['high', 'tinggi']) => 'Beban Tinggi',
            Str::contains($value, ['medium', 'sedang']) => 'Beban Sedang',
            Str::contains($value, ['low', 'rendah']) => 'Beban Rendah',
            blank($value) => '-',
            default => Str::headline($value),
        };
    }

    private function workloadColor(?string $state): string
    {
        $value = Str::lower(trim((string) $state));

        return match (true) {
            Str::contains($value, ['high', 'tinggi']) => 'danger',
            Str::contains($value, ['medium', 'sedang']) => 'warning',
            Str::contains($value, ['low', 'rendah']) => 'success',
            default => 'gray',
        };
    }

    public static function canView(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && $user->role === UserRole::PIMPINAN;
    }
}
