<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Department;
use App\Models\Catatan;

class UserProfileController extends Controller
{
    // Menampilkan profil mahasiswa
    public function index()
    {
        $user = Auth::user()->load('student.departemen');

        $departments = Department::orderBy('name')->get();

        // Nilai statistik sementara
        $stats = [
            'total_catatan' => Catatan::where('user_id', $user->id)
                ->where('status', 'Terbit')
                ->where('visibilitas', 'publik')
                ->count(),

            'total_upvote' => Catatan::where('user_id', $user->id)
                ->where('status', 'Terbit')
                ->where('visibilitas', 'publik')
                ->sum('upvote'),
        ];

        return view('user.profile', compact(
            'user',
            'departments',
            'stats'
        ));
    }

    // Memperbarui profil mahasiswa
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'student_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('students', 'student_number')
                    ->ignore($user->student?->id),
            ],

            'department_id' => [
                'nullable',
                'exists:departments,id',
            ],

            'enrollment_year' => [
                'nullable',
                'integer',
                'digits:4',
            ],

            'gender' => [
                'nullable',
                Rule::in(['male', 'female']),
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',

            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username sudah digunakan.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'student_number.required' => 'NIM wajib diisi.',
            'student_number.string' => 'NIM harus berupa teks.',
            'student_number.max' => 'NIM maksimal 20 karakter.',
            'student_number.unique' => 'NIM sudah terdaftar.',

            'department_id.exists' => 'Departemen yang dipilih tidak valid.',

            'enrollment_year.integer' => 'Tahun masuk harus berupa angka.',
            'enrollment_year.digits' => 'Tahun masuk harus terdiri dari 4 angka.',

            'gender.in' => 'Jenis kelamin yang dipilih tidak valid.',

            'profile_photo.image' => 'File yang diunggah harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat JPG, JPEG, PNG, atau WEBP.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        // Simpan foto baru jika pengguna mengunggah foto
        $oldPhoto = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request
                ->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        // Update data akun
        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'profile_photo' => $validated['profile_photo'] ?? $oldPhoto,
        ]);

        // Hapus foto lama setelah data berhasil diperbarui
        if (
            $request->hasFile('profile_photo') &&
            $oldPhoto
        ) {
            Storage::disk('public')->delete($oldPhoto);
        }

        // Update data mahasiswa
        $user->student()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'student_number' => $validated['student_number'],
                'department_id' => $validated['department_id'] ?? null,
                'enrollment_year' => $validated['enrollment_year'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]
        );

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // Menghapus foto profil
    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);

            $user->update([
                'profile_photo' => null,
            ]);
        }

        return redirect()
            ->route('user.profile')
            ->with('success', 'Foto profil berhasil dihapus.');
    }

    // Memperbarui password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',

            'password.required' => 'Password baru wajib diisi.',
            'password.string' => 'Password baru harus berupa teks.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('user.profile')
            ->with('success', 'Password berhasil diperbarui.');
    }

    // Menonaktifkan akun mahasiswa
    // Menghapus akun mahasiswa
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'current_password',
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.current_password' => 'Password yang dimasukkan tidak sesuai.',
        ]);

        $user = Auth::user();

        // Tandai akun sebagai dihapus
        $user->update([
            'status' => 'deleted',
        ]);

        // Logout pengguna
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
