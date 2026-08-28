<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\UserRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('nomor')
                    ->label('No.')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->placeholder('-')
                    ->copyable(),

                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->sortable(),

                TextColumn::make('section.name')
                    ->label('Seksi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Tidak memiliki seksi')
                    ->wrap(),

                IconColumn::make('is_active')
                    ->label('Akun Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('email_verified_at')
                    ->label('Email Diverifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum diverifikasi')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran')
                    ->options(UserRole::formOptions()),

                SelectFilter::make('section_id')
                    ->label('Seksi')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Status Akun')
                    ->trueLabel('Akun Aktif')
                    ->falseLabel('Akun Tidak Aktif')
                    ->placeholder('Semua Status'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat'),

                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus yang Dipilih')
                        ->modalHeading('Hapus pengguna')
                        ->modalDescription('Apakah Anda yakin ingin menghapus pengguna yang dipilih?')
                        ->modalSubmitActionLabel('Hapus')
                        ->modalCancelActionLabel('Batal'),
                ])
                    ->label('Tindakan'),
            ])
            ->emptyStateHeading('Belum ada data pengguna')
            ->emptyStateDescription('Tambahkan pengguna untuk mengelola akun masyarakat, admin seksi, dan pimpinan.')
            ->emptyStateIcon('heroicon-o-users');
    }
}