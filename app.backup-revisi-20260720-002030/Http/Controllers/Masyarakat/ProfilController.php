<?php

namespace App\Http\Controllers\Masyarakat;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class ProfilController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = $this->penggunaMasyarakat($request);

        return view('masyarakat.profil.index', compact('pengguna'));
    }

    public function update(Request $request): RedirectResponse
    {
        $pengguna = $this->penggunaMasyarakat($request);

        $data = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:255',
                ],
                'phone' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^(?:\+62|62|0)[0-9]{8,13}$/',
                ],
                'address' => [
                    'required',
                    'string',
                    'min:10',
                    'max:1000',
                ],
                'foto' => [
                    'bail',
                    'nullable',
                    'file',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'mimetypes:image/jpeg,image/png,image/webp',
                    'extensions:jpg,jpeg,png,webp',
                    'max:3072',
                    'dimensions:min_width=300,min_height=300,max_width=4000,max_height=4000',
                ],
                'hapus_foto' => [
                    'nullable',
                    'boolean',
                ],
            ],
            [
                'name.required' => 'Nama lengkap wajib diisi.',
                'name.string' => 'Nama lengkap harus berupa teks.',
                'name.min' => 'Nama lengkap minimal 3 karakter.',
                'name.max' => 'Nama lengkap maksimal 255 karakter.',
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.regex' => 'Format nomor telepon tidak valid.',
                'phone.max' => 'Nomor telepon maksimal 20 karakter.',
                'address.required' => 'Alamat lengkap wajib diisi.',
                'address.string' => 'Alamat lengkap harus berupa teks.',
                'address.min' => 'Alamat lengkap minimal 10 karakter.',
                'address.max' => 'Alamat lengkap maksimal 1000 karakter.',
                'foto.file' => 'Foto profil harus berupa berkas.',
                'foto.image' => 'Berkas yang dipilih harus berupa gambar.',
                'foto.mimes' => 'Foto profil hanya boleh menggunakan format JPG, JPEG, PNG, atau WEBP.',
                'foto.mimetypes' => 'Jenis berkas foto tidak didukung.',
                'foto.extensions' => 'Ekstensi foto hanya boleh .jpg, .jpeg, .png, atau .webp.',
                'foto.max' => 'Ukuran foto profil maksimal 3 MB.',
                'foto.dimensions' => 'Dimensi foto minimal 300 × 300 px dan maksimal 4000 × 4000 px.',
                'hapus_foto.boolean' => 'Pilihan hapus foto tidak valid.',
            ],
        );

        $fotoLama = $pengguna->profile_photo;
        $fotoBaru = null;
        $hapusFoto = $request->boolean('hapus_foto');

        if ($request->hasFile('foto')) {
            $fotoBaru = $request->file('foto')->store(
                'foto-profil',
                'public',
            );
        }

        try {
            $pengguna->update([
                'name' => trim($data['name']),
                'phone' => trim($data['phone']),
                'address' => trim($data['address']),
                'profile_photo' => $fotoBaru
                    ?? ($hapusFoto ? null : $fotoLama),
            ]);
        } catch (Throwable $exception) {
            if ($fotoBaru) {
                Storage::disk('public')->delete($fotoBaru);
            }

            report($exception);

            return back()
                ->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Profil gagal diperbarui',
                    'text' => 'Terjadi kesalahan saat menyimpan perubahan profil.',
                    'confirmButtonText' => 'Mengerti',
                ]);
        }

        if ($fotoLama && ($fotoBaru || $hapusFoto)) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('masyarakat.profil.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Profil diperbarui',
                'text' => 'Data dan foto profil Anda berhasil disimpan.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    public function kataSandi(Request $request): View
    {
        $pengguna = $this->penggunaMasyarakat($request);

        return view(
            'masyarakat.profil.kata-sandi',
            compact('pengguna'),
        );
    }

    public function updateKataSandi(Request $request): RedirectResponse
    {
        $pengguna = $this->penggunaMasyarakat($request);

        $data = $request->validate(
            [
                'current_password' => [
                    'required',
                    'current_password:web',
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers(),
                ],
            ],
            [
                'current_password.required' => 'Kata sandi saat ini wajib diisi.',
                'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
                'password.required' => 'Kata sandi baru wajib diisi.',
                'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
                'password.min' => 'Kata sandi baru minimal 8 karakter.',
            ],
        );

        $pengguna->update([
            'password' => Hash::make($data['password']),
        ]);

        $request->session()->regenerate();

        return redirect()
            ->route('masyarakat.profil.kata-sandi')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Kata sandi diperbarui',
                'text' => 'Kata sandi akun Anda berhasil diganti.',
                'confirmButtonText' => 'Selesai',
            ]);
    }

    private function penggunaMasyarakat(Request $request): User
    {
        $pengguna = $request->user();

        $role = $pengguna?->role instanceof UserRole
            ? $pengguna->role
            : UserRole::tryFrom((string) $pengguna?->role);

        abort_unless(
            $pengguna instanceof User
            && $role === UserRole::MASYARAKAT
            && $pengguna->is_active,
            403,
        );

        return $pengguna;
    }
}