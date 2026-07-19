<?php

namespace App\Http\Controllers\Autentikasi;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class RegistrasiMasyarakatController extends Controller
{
    /**
     * Menampilkan halaman registrasi masyarakat.
     */
    public function create(): View
    {
        return view('autentikasi.register');
    }

    /**
     * Menyimpan akun masyarakat baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => trim(
                (string) $request->input('name'),
            ),

            'nik' => preg_replace(
                '/\D/',
                '',
                (string) $request->input('nik'),
            ),

            'phone' => preg_replace(
                '/[\s\-()]/',
                '',
                trim((string) $request->input('phone')),
            ),

            'email' => Str::lower(
                trim((string) $request->input('email')),
            ),

            'address' => trim(
                (string) $request->input('address'),
            ),
        ]);

        $data = $request->validateWithBag(
            'register',
            [
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                ],

                'nik' => [
                    'required',
                    'digits:16',
                    Rule::unique('users', 'nik'),
                ],

                'phone' => [
                    'required',
                    'string',
                    'regex:/^(?:\+62|62|0)8[0-9]{8,12}$/',
                    'max:20',
                ],

                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email'),
                ],

                'address' => [
                    'required',
                    'string',
                    'min:10',
                    'max:1000',
                ],

                'password' => [
                    'required',
                    'string',
                    'confirmed',

                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],

                'agreement' => [
                    'required',
                    'accepted',
                ],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.min' => 'Nama lengkap minimal 3 karakter.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',

                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus terdiri dari tepat 16 digit.',
                'nik.unique' => 'NIK tersebut sudah terdaftar.',

                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon tidak valid.',
                'phone.regex' => 'Gunakan nomor Indonesia yang valid, misalnya 081234567890.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',

                'email.required' => 'Alamat email wajib diisi.',
                'email.string' => 'Alamat email harus berupa teks.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.unique' => 'Alamat email tersebut sudah terdaftar.',
                'email.max' => 'Alamat email maksimal 255 karakter.',

                'address.required' => 'Alamat lengkap wajib diisi.',
                'address.string' => 'Alamat lengkap harus berupa teks.',
                'address.min' => 'Alamat lengkap minimal 10 karakter.',
                'address.max' => 'Alamat lengkap maksimal 1000 karakter.',

                'password.required' => 'Kata sandi wajib diisi.',
                'password.string' => 'Kata sandi harus berupa teks.',
                'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
                'password.min' => 'Kata sandi minimal 8 karakter.',
                'password.letters' => 'Kata sandi harus memiliki huruf.',
                'password.mixed' => 'Kata sandi harus memiliki huruf besar dan huruf kecil.',
                'password.numbers' => 'Kata sandi harus memiliki minimal satu angka.',
                'password.symbols' => 'Kata sandi harus memiliki minimal satu simbol.',

                'agreement.required' => 'Persetujuan penggunaan data wajib dicentang.',
                'agreement.accepted' => 'Anda harus menyetujui penggunaan data.',
            ],
        );

        try {
            DB::transaction(function () use ($data): void {
                User::query()->create([
                    'name' => $data['name'],
                    'nik' => $data['nik'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'address' => $data['address'],

                    'password' => Hash::make(
                        $data['password'],
                    ),

                    'role' => UserRole::MASYARAKAT->value,
                    'section_id' => null,
                    'is_active' => true,
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('register')
                ->withInput(
                    $request->except([
                        'password',
                        'password_confirmation',
                    ]),
                )
                ->withErrors(
                    [
                        'register' => 'Akun belum dapat disimpan. Silakan mencoba kembali.',
                    ],
                    'register',
                )
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Pendaftaran gagal',
                    'text' => 'Akun belum dapat disimpan. Silakan mencoba kembali.',
                    'confirmButtonText' => 'Mengerti',
                ]);
        }

        return redirect()
            ->route('login')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Pendaftaran berhasil',
                'text' => 'Akun berhasil dibuat. Silakan masuk menggunakan email dan kata sandi Anda.',
                'confirmButtonText' => 'Masuk sekarang',
            ]);
    }
}