<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Autentikasi - CatatIn</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.3.0/iconify-icon.min.js"></script>

    <!-- CSS CatatIn -->
    @vite(['resources/css/auth.css', 'resources/js/auth.js'])

</head>

<body data-active-tab="{{ $active_tab ?? 'masuk' }}">

    @php
        $isDaftar = ($active_tab ?? 'masuk') === 'daftar';
    @endphp

    <main class="card-container">

        <!-- ==========================================
             PANEL KIRI: BRANDING CATATIN
        =========================================== -->
        <section class="left-panel">

            <div class="logo">
                <div class="logo-icon">
                    <iconify-icon icon="mdi:book-open-page-variant"></iconify-icon>
                </div>

                <span>CatatIn</span>
            </div>

            <div class="left-content">

                <h1 class="headline">
                    Belajar Bersama,
                    <span class="highlight">Lebih Bermakna.</span>
                </h1>

                <p class="description">
                    Tempat berbagi catatan perkuliahan dan menemukan
                    wawasan baru bersama mahasiswa lainnya.
                </p>

                <div class="feature-list">

                    <div class="feature-item">
                        <div class="check-icon">
                            <iconify-icon icon="lucide:check"></iconify-icon>
                        </div>

                        <span>Bagikan catatan dengan mudah</span>
                    </div>

                    <div class="feature-item">
                        <div class="check-icon">
                            <iconify-icon icon="lucide:check"></iconify-icon>
                        </div>

                        <span>Temukan referensi belajar</span>
                    </div>

                    <div class="feature-item">
                        <div class="check-icon">
                            <iconify-icon icon="lucide:check"></iconify-icon>
                        </div>

                        <span>Belajar bersama mahasiswa lainnya</span>
                    </div>

                </div>
            </div>

            <div class="copyright">
                &copy; {{ date('Y') }} CatatIn. All rights reserved.
            </div>

        </section>


        <!-- ==========================================
             PANEL KANAN: FORM AUTENTIKASI
        =========================================== -->
        <section class="right-panel">

            <div class="auth-section">

                <!-- TAB MASUK DAN DAFTAR -->
                <div class="tab-switcher">

                    <button type="button" id="btn-masuk" data-url="{{ route('login') }}"
                        class="tab-btn {{ !$isDaftar ? 'active' : '' }}">
                        Masuk
                    </button>

                    <button type="button" id="btn-daftar" data-url="{{ route('register') }}"
                        class="tab-btn {{ $isDaftar ? 'active' : '' }}">
                        Daftar
                    </button>

                </div>


                <!-- PESAN SUKSES -->
                @if (session('success'))
                    <div class="inline-success">
                        {{ session('success') }}
                    </div>
                @endif


                <!-- PESAN ERROR UMUM -->
                @if ($errors->has('login'))
                    <div class="inline-error">
                        {{ $errors->first('login') }}
                    </div>
                @endif


                <!-- ==================================
                     FORM MASUK
                =================================== -->
                <div id="form-masuk" class="auth-form" style="{{ $isDaftar ? 'display: none;' : '' }}">

                    <div class="form-header">
                        <h2>Selamat Datang Kembali!</h2>

                        <p>
                            Masuk ke akun CatatIn kamu untuk melanjutkan.
                        </p>
                    </div>

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf

                        <!-- EMAIL / NIM -->
                        <div class="input-group">

                            <label for="identifier">
                                Email atau NIM
                                <span class="required">*</span>
                            </label>

                            <input type="text" id="identifier" name="identifier"
                                placeholder="Masukkan email atau NIM" value="{{ old('identifier') }}"
                                autocomplete="username">

                            @error('identifier')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- PASSWORD -->
                        <div class="input-group">

                            <label for="login-password">
                                Password
                                <span class="required">*</span>
                            </label>

                            <div class="password-wrapper">

                                <input type="password" id="login-password" name="password"
                                    placeholder="Masukkan password" autocomplete="current-password">

                                <button type="button" class="toggle-password-btn" data-target="login-password"
                                    aria-label="Tampilkan password">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>

                            </div>

                            @error('password')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- INGAT SAYA -->
                        <div class="form-options">

                            <label class="checkbox-group">
                                <input type="checkbox" name="remember" value="1">

                                <span>Ingat saya</span>
                            </label>

                            <a href="#" class="forgot-password">
                                Lupa password?
                            </a>

                        </div>


                        <!-- TOMBOL MASUK -->
                        <button type="submit" class="btn-submit">
                            Masuk
                        </button>

                    </form>


                    <!-- PEMBATAS -->
                    <div class="divider">
                        <span>atau masuk dengan</span>
                    </div>


                    <!-- GOOGLE -->
                    <button type="button" class="btn-google" disabled>
                        <iconify-icon icon="logos:google-icon"></iconify-icon>

                        Google
                    </button>

                </div>



                <!-- ==================================
                     FORM DAFTAR
                =================================== -->
                <div id="form-daftar" class="auth-form" style="{{ !$isDaftar ? 'display: none;' : '' }}">

                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>

                        <p>
                            Daftar sekarang dan mulai berbagi catatan.
                        </p>
                    </div>

                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf

                        <!-- NAMA LENGKAP -->
                        <div class="input-group">

                            <label for="name">
                                Nama Lengkap
                                <span class="required">*</span>
                            </label>

                            <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap"
                                value="{{ old('name') }}" autocomplete="name">

                            @error('name')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- NIM -->
                        <div class="input-group">

                            <label for="student_number">
                                NIM
                                <span class="required">*</span>
                            </label>

                            <input type="text" id="student_number" name="student_number"
                                placeholder="Masukkan NIM" value="{{ old('student_number') }}" inputmode="numeric">

                            <small class="field-hint">
                                10–20 digit angka
                            </small>

                            @error('student_number')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- EMAIL KAMPUS -->
                        <div class="input-group">

                            <label for="email">
                                Email Kampus
                                <span class="required">*</span>
                            </label>

                            <input type="email" id="email" name="email" placeholder="Masukkan email kampus"
                                value="{{ old('email') }}" autocomplete="email">

                            @error('email')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- PASSWORD -->
                        <div class="input-group">

                            <label for="register-password">
                                Password
                                <span class="required">*</span>
                            </label>

                            <div class="password-wrapper">

                                <input type="password" id="register-password" name="password"
                                    placeholder="Buat password" autocomplete="new-password">

                                <button type="button" class="toggle-password-btn" data-target="register-password"
                                    aria-label="Tampilkan password">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>

                            </div>

                            @error('password')
                                <small class="input-error">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        <!-- KONFIRMASI PASSWORD -->
                        <div class="input-group">

                            <label for="password_confirmation">
                                Konfirmasi Password
                                <span class="required">*</span>
                            </label>

                            <div class="password-wrapper">

                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    placeholder="Ulangi password" autocomplete="new-password">

                                <button type="button" class="toggle-password-btn"
                                    data-target="password_confirmation" aria-label="Tampilkan password">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>

                            </div>

                            <div id="password-match-message" class="field-hint"></div>

                        </div>


                        <!-- PERSETUJUAN -->
                        <div class="checkbox-group">

                            <input type="checkbox" id="terms" name="terms" value="1">

                            <span>
                                Saya menyetujui Syarat dan Ketentuan
                                <span class="required" style="color: red">*</span>
                            </span>

                        </div>

                        @error('terms')
                            <small class="input-error">
                                {{ $message }}
                            </small>
                        @enderror


                        <!-- TOMBOL DAFTAR -->
                        <button type="submit" class="btn-submit">
                            Daftar Sekarang
                        </button>

                    </form>


                    <!-- PEMBATAS -->
                    <div class="divider">
                        <span>atau daftar dengan</span>
                    </div>


                    <!-- GOOGLE -->
                    <button type="button" class="btn-google" disabled>
                        <iconify-icon icon="logos:google-icon"></iconify-icon>

                        Google
                    </button>

                </div>

            </div>

        </section>

    </main>



</body>

</html>
