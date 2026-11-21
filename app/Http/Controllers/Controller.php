<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /** Pengguna yang sedang login, bertipe User supaya editor mengenali method-nya. */
    protected function pengguna(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    /** Akun diblokir: keluarkan dari sesi. */
    protected function tolakJikaDiblokir(): void
    {
        $user = Auth::user();

        if ($user && $user->isDiblokir()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            throw new HttpResponseException(
                redirect()->route('login')
                    ->withErrors(['identifier' => 'Akun kamu diblokir. Hubungi admin untuk informasi lebih lanjut.'])
            );
        }
    }

    /** Akun nonaktif: hanya boleh melihat. Sekaligus cek diblokir. */
    protected function tolakJikaNonaktif(): void
    {
        $this->tolakJikaDiblokir();

        if ($this->pengguna()->isNonaktif()) {
            throw new HttpResponseException(
                redirect()->route('catatan.index')
                    ->with('galat', 'Akun kamu nonaktif, jadi hanya bisa melihat catatan.')
            );
        }
    }
}