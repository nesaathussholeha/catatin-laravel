@extends('layouts.admin')

@section('title', 'Profil Admin - CatatIn')
@section('page-heading', 'Profil Admin')

@push('styles')
    @vite('resources/css/profil-admin.css')
@endpush

@push('scripts')
    @vite('resources/js/profil-admin.js')
@endpush

@section('content')
<div class="profil-admin">

    {{-- Kartu ringkasan --}}
    <section class="pa-stats">
        <article class="pa-card pa-stat">
            <div class="pa-stat-ico">
                <iconify-icon icon="lucide:shield-check"></iconify-icon>
            </div>
            <div>
                <b>Administrator</b>
                <p>Peran akun</p>
            </div>
        </article>

        <article class="pa-card pa-stat">
            <div class="pa-stat-ico">
                <iconify-icon icon="lucide:user-check"></iconify-icon>
            </div>
            <div>
                <b>{{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}</b>
                <p>Status akun</p>
            </div>
        </article>

        <article class="pa-card pa-stat">
            <div class="pa-stat-ico">
                <iconify-icon icon="lucide:calendar"></iconify-icon>
            </div>
            <div>
                <b>{{ $user->created_at?->format('d/m/Y') ?? '-' }}</b>
                <p>Bergabung sejak</p>
            </div>
        </article>
    </section>

    <div class="pa-grid">

        {{-- Informasi Akun --}}
        <section class="pa-card pa-panel">
            <h2>Informasi Akun</h2>
            <p class="pa-hint">Perbarui nama, username, dan email administrator.</p>

            @if (session('success'))
                <div class="pa-alert" role="status">{{ session('success') }}</div>
            @endif

            <div class="pa-identity">
                <div class="pa-avatar">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
                <div>
                    <strong>{{ $user->name }}</strong>
                    <small>{{ '@'.($user->username ?? '-') }} &middot; {{ $user->email }}</small>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profil.update') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="pa-field">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" maxlength="100"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="pa-err">{{ $message }}</p> @enderror
                </div>

                <div class="pa-field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" maxlength="50"
                           value="{{ old('username', $user->username) }}" required>
                    @error('username') <p class="pa-err">{{ $message }}</p> @enderror
                </div>

                <div class="pa-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" maxlength="100"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="pa-err">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="pa-btn">Simpan Perubahan</button>
            </form>
        </section>

        {{-- Keamanan Akun --}}
        <section class="pa-card pa-panel">
            <h2>Keamanan Akun</h2>
            <p class="pa-hint">Ubah password untuk menjaga keamanan akun.</p>

            @if (session('success_password'))
                <div class="pa-alert" role="status">{{ session('success_password') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.profil.password') }}" novalidate>
                @csrf
                @method('PUT')

                @foreach ([
                    ['password_lama', 'Password Lama', 'current-password'],
                    ['password_baru', 'Password Baru', 'new-password'],
                    ['password_baru_confirmation', 'Konfirmasi Password Baru', 'new-password'],
                ] as [$nama, $label, $ac])
                    <div class="pa-field">
                        <label for="{{ $nama }}">{{ $label }}</label>
                        <div class="pa-pass">
                            <input type="password" id="{{ $nama }}" name="{{ $nama }}"
                                   autocomplete="{{ $ac }}" required>
                            <button type="button" class="toggle-password"
                                    data-target="{{ $nama }}" aria-label="Tampilkan password">
                                <iconify-icon icon="lucide:eye"></iconify-icon>
                            </button>
                        </div>
                        @if ($nama !== 'password_baru_confirmation')
                            @error($nama) <p class="pa-err">{{ $message }}</p> @enderror
                        @endif
                    </div>
                @endforeach

                <p class="pa-note">Minimal 8 karakter, mengandung huruf dan angka.</p>

                <button type="submit" class="pa-btn">Ganti Password</button>
            </form>
        </section>

    </div>
</div>
@endsection