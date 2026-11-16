<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.index', [
            'active_tab' => 'daftar'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'student_number' => [
                'required',
                'string',
                'max:20',
                'unique:students,student_number',
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'student_number.required' => 'NIM wajib diisi.',
            'student_number.unique' => 'NIM sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung minimal satu huruf kapital dan satu huruf kecil.',
            'password.numbers' => 'Password harus mengandung minimal satu angka.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'student',
                'status' => 'active',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_number' => $validated['student_number'],
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat. Silakan masuk.');
    }
}
