<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.index', [
            'active_tab' => 'masuk'
        ]);
    }

    // Memproses login
    public function store(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'identifier.required' => 'Email, NIM, atau username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $identifier = $request->identifier;

        // Mencari akun berdasarkan email, username, atau NIM
        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->orWhereHas('student', function ($query) use ($identifier) {
                $query->where('student_number', $identifier);
            })
            ->first();

        // Memeriksa akun dan password
        if (
            !$user ||
            !Hash::check($request->password, $user->password) ||
            $user->status === 'blocked'
        ) {
            return back()
                ->withErrors([
                    'identifier' => $user && $user->status === 'blocked'
                        ? 'Akun kamu telah diblokir dan tidak dapat login.'
                        : 'Email, NIM, username, atau password tidak sesuai.',
                ])
                ->onlyInput('identifier');
        }

        // Login dan regenerasi session
        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Mengarahkan pengguna berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    // Logout
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Kamu berhasil logout.');
    }
}
