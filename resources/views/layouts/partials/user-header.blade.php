@php
    $user = auth()->user();

    $userName = $user->name ?? 'Mahasiswa';

    $userRole = $user->role === 'student' ? 'Mahasiswa' : ucfirst($user->role);

    $userInitial = strtoupper(substr($userName, 0, 1));
@endphp

<header class="top-header">

    <div class="header-left">

        <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu">
            <iconify-icon icon="lucide:menu"></iconify-icon>
        </button>

        <h1 class="page-title">
            @yield('page-title', 'Dashboard')
        </h1>

    </div>

    <a href="{{ route('user.profile') }}" class="user-profile-link">
        <div class="user-profile">

            <div class="user-info">
                <span class="user-name">
                    {{ $userName }}
                </span>

                <span class="user-role">
                    {{ $userRole }}
                </span>
            </div>

            <div class="avatar">
                {{ $userInitial }}
            </div>

        </div>
    </a>

</header>
