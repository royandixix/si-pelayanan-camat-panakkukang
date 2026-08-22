<?php

namespace App\Filament\Pages\Auth;

use App\Enums\UserRole;
use Filament\Auth\Pages\Login;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class LoginAdmin extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getRoleFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->label('Masuk Sebagai')
            ->options([
                UserRole::SUPER_ADMIN->value => UserRole::SUPER_ADMIN->label(),
                UserRole::ADMIN_SEKSI->value => UserRole::ADMIN_SEKSI->label(),
            ])
            ->placeholder('Pilih role akun')
            ->native(false)
            ->required();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('nik')
            ->label('NIK')
            ->placeholder('Masukkan NIK')
            ->autocomplete('username')
            ->required()
            ->maxLength(20);
    }

    protected function getCredentialsFromFormData(
        #[SensitiveParameter] array $data,
    ): array {
        return [
            'nik' => trim((string) $data['nik']),
            'password' => $data['password'],
            'role' => $data['role'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.nik' => 'NIK, kata sandi, atau role yang dipilih tidak sesuai.',
        ]);
    }

    public function getHeading(): string
    {
        return 'Masuk ke Panel Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Pilih role akun, lalu masukkan NIK dan kata sandi.';
    }
}