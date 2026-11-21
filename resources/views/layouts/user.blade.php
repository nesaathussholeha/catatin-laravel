<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - CatatIn</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/2.3.0/iconify-icon.min.js"></script>

    {{-- CSS Layout User --}}
    @vite(['resources/css/user-layout.css', 'resources/js/user-layout.js'])

    @stack('styles')
</head>

<body>

    <div class="app-container">

        {{-- Sidebar --}}
        @include('layouts.partials.user-sidebar')

        {{-- Overlay untuk tampilan mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="main-wrapper">

            {{-- Header --}}
            @include('layouts.partials.user-header')

            {{-- Konten halaman --}}
            <main class="content-area">
                @yield('content')
            </main>

        </div>

    </div>

    <div class="logout-modal" id="logout-modal">
        <div class="logout-modal-content">
            <div class="logout-modal-icon">
                <iconify-icon icon="lucide:log-out"></iconify-icon>
            </div>

            <h3>Konfirmasi Keluar</h3>

            <p>Apakah kamu yakin ingin keluar dari akun CatatIn?</p>

            <div class="logout-modal-actions">
                <button type="button" id="cancel-logout">
                    Batal
                </button>

                <button type="button" id="confirm-logout">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')

</body>

</html>
