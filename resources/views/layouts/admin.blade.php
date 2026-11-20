<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Admin - CatatIn')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    @vite(['resources/css/admin-layout.css', 'resources/js/admin-layout.js'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>

    <div class="admin-wrapper">

        {{-- SIDEBAR ADMIN --}}
        @include('layouts.partials.admin-sidebar')

        {{-- KONTEN UTAMA --}}
        <div class="main-content">

            {{-- HEADER ADMIN --}}
            @include('layouts.partials.admin-header')

            {{-- ISI HALAMAN --}}
            <main class="content">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>
