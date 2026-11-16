
@php
    $admin = auth()->user();

    $adminName = $admin->name ?? 'Administrator';
    $adminInitial = strtoupper(substr($adminName, 0, 1));
@endphp

<header class="admin-header">

    <div class="admin-header-left">

        <button type="button" class="admin-menu-toggle" id="admin-menu-toggle">
            <iconify-icon icon="lucide:menu"></iconify-icon>
        </button>

        <div class="admin-page-heading">
            <h1>@yield('page-heading', 'Dashboard')</h1>
        </div>

    </div>

    <div class="admin-header-right">

        <a href="#" class="admin-profile-link">
            <div class="admin-profile">

                <div class="admin-profile-info">
                    <span class="admin-profile-name">{{ $adminName }}</span>
                    <span class="admin-profile-role">Administrator</span>
                </div>

                <div class="admin-avatar">
                    {{ $adminInitial }}
                </div>

            </div>
        </a>

    </div>

</header>
