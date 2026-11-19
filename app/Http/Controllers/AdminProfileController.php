<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profil', ['user' => auth()->user()]);
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'     => ['required', 'string', 'min:3', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash',
                           Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:100',
                           Rule::unique('users', 'email')->ignore($user->id)],
        ], $this->messages());

        $user->forceFill($data)->save();

        return redirect()->route('admin.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => ['required', 'current_password'],
            'password_baru' => ['required', 'confirmed', 'different:password_lama',
                                Password::min(8)->letters()->numbers()],
        ], $this->messages());

        auth()->user()->forceFill([
            'password' => Hash::make($request->password_baru),
        ])->save();

        $request->session()->regenerate();

        return redirect()->route('admin.profil')
            ->with('success_password', 'Password berhasil diganti.');
    }

    private function messages(): array
    {
        return [
            'name.required'                  => 'Nama lengkap wajib diisi.',
            'name.min'                       => 'Nama minimal 3 karakter.',
            'username.required'              => 'Username wajib diisi.',
            'username.min'                   => 'Username minimal 3 karakter.',
            'username.alpha_dash'            => 'Username hanya boleh huruf, angka, strip, dan garis bawah.',
            'username.unique'                => 'Username sudah dipakai.',
            'email.required'                 => 'Email wajib diisi.',
            'email.email'                    => 'Format email tidak valid.',
            'email.unique'                   => 'Email sudah dipakai.',
            'password_lama.required'         => 'Password lama wajib diisi.',
            'password_lama.current_password' => 'Password lama salah.',
            'password_baru.required'         => 'Password baru wajib diisi.',
            'password_baru.confirmed'        => 'Konfirmasi password tidak sesuai.',
            'password_baru.different'        => 'Password baru tidak boleh sama dengan yang lama.',
            'password_baru.min'              => 'Password baru minimal 8 karakter.',
        ];
    }
}