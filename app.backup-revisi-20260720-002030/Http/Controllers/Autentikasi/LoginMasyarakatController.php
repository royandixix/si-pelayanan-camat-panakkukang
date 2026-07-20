<?php

namespace App\Http\Controllers\Autentikasi;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginMasyarakatController extends Controller
{
    /**
     * Menampilkan halaman login masyarakat.
     */
    public function create(): View
    {
        return view('autentikasi.login');
    }

    /**
     * Memproses login masyarakat.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(
                trim((string) $request->input('email')),
            ),
        ]);

        $data = $request->validateWithBag(
            'login',
            [
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:8',
                ],

                'remember' => [
                    'nullable',
                    'boolean',
                ],
            ],
            [
                'email.required' => 'Alamat email wajib diisi.',
                'email.string' => 'Alamat email harus berupa teks.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.max' => 'Alamat email maksimal 255 karakter.',

                'password.required' => 'Kata sandi wajib diisi.',
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.min' => 'Kata sandi minimal 8 karakter.',

                'remember.boolean' => 'Pilihan ingat akun tidak valid.',
            ],
        );

        $berhasil = Auth::attempt(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => UserRole::MASYARAKAT->value,
                'is_active' => true,
            ],
            $request->boolean('remember'),
        );

        if (! $berhasil) {
            return redirect()
                ->route('login')
                ->withInput(
                    $request->only([
                        'email',
                        'remember',
                    ]),
                )
                ->withErrors(
                    [
                        'email' => 'Email atau kata sandi tidak sesuai, akun tidak aktif, atau akun bukan masyarakat.',
                    ],
                    'login',
                )
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Login belum berhasil',
                    'text' => 'Periksa kembali email dan kata sandi yang Anda masukkan.',
                    'confirmButtonText' => 'Periksa kembali',
                ]);
        }

        $request->session()->regenerate();

        return redirect()
            ->intended('/masyarakat')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Login berhasil',
                'text' => 'Selamat datang di Portal Pelayanan Masyarakat.',
                'confirmButtonText' => 'Lanjutkan',
            ]);
    }

    /**
     * Mengeluarkan pengguna dari sistem.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil keluar',
                'text' => 'Anda telah keluar dari akun dengan aman.',
                'confirmButtonText' => 'Mengerti',
            ]);
    }
}