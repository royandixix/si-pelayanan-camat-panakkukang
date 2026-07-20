<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Illuminate\Http\RedirectResponse;

class LogoutPetugasResponse implements LogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        return new RedirectResponse(
            route('filament.petugas.auth.login'),
        );
    }
}
