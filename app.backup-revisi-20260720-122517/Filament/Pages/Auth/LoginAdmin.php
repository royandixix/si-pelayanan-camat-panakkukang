<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class LoginAdmin extends Login
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('nik')
            ->label('NIK')
            ->placeholder('Masukkan NIK admin')
            ->autocomplete('username')
            ->required()
            ->maxLength(20)
            ->autofocus();
    }

    protected function getCredentialsFromFormData(
        #[SensitiveParameter] array $data,
    ): array {
        return [
            'nik' => trim((string) $data['nik']),
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.nik' => 'NIK atau kata sandi tidak sesuai.',
        ]);
    }

    public function getHeading(): string
    {
        return 'Masuk ke Panel Admin';
    }

    public function getSubheading(): ?string
    {
        return 'Gunakan NIK dan kata sandi yang diberikan oleh Super Admin.';
    }
}
