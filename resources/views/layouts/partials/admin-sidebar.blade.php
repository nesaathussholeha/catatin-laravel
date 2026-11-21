<aside class="admin-sidebar" id="admin-sidebar">

    <div class="admin-brand">
        <div class="brand-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>

        <div class="brand-text">
            <h2>CatatIn</h2>
            <span>Administrator</span>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <nav class="admin-nav">

        <span class="nav-label">MENU UTAMA</span>

        <a href="{{ route('admin.dashboard') }}"
            class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <iconify-icon icon="lucide:layout-dashboard"></iconify-icon>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.kelola-catatan.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.kelola-catatan.*') ? 'active' : '' }}">
    <iconify-icon icon="lucide:book-copy"></iconify-icon>
    <span>Kelola Catatan</span>
</a>
        <a href="{{ route('admin.user-reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.user-reports.index') ? 'active' : '' }}">
            <iconify-icon icon="lucide:users"></iconify-icon>
            <span>Kelola Pengguna</span>
        </a>

        <a href="{{  route('admin.master-data.index') }}" class="admin-nav-link {{ request()->routeIs('admin.master-data.index') ? 'active' : '' }}">
            <iconify-icon icon="lucide:database"></iconify-icon>
            <span>Master Data</span>
        </a>

        <a href="{{ route('admin.kelola-laporan') }}" class="admin-nav-link {{ request()->routeIs('admin.kelola-laporan') ? 'active' : '' }}">
            <iconify-icon icon="lucide:flag"></iconify-icon>
            <span>Kelola Laporan</span>
        </a>

        <span class="nav-label nav-label-bottom">PENGATURAN</span>

        <a href="{{ route('admin.profil') }}"
            class="admin-nav-link {{ request()->routeIs('admin.profil*') ? 'active' : '' }}">
            <iconify-icon icon="lucide:user-round"></iconify-icon>
            <span>Profil</span>
        </a>

    </nav>

    {{-- LOGOUT --}}
    <div class="admin-sidebar-footer">

        <form action="{{ route('logout') }}" method="POST" id="admin-logout-form">
            @csrf

            <button type="button" class="admin-logout-btn" id="admin-logout-trigger">
                <iconify-icon icon="lucide:log-out"></iconify-icon>
                <span>Keluar</span>
            </button>
        </form>

    </div>

</aside>

{{-- OVERLAY MOBILE --}}
<div class="admin-sidebar-overlay" id="admin-sidebar-overlay"></div>

{{-- MODAL LOGOUT --}}
<div class="admin-logout-modal" id="admin-logout-modal">
    <div class="admin-logout-modal-content">

        <div class="admin-logout-icon">
            <iconify-icon icon="lucide:log-out"></iconify-icon>
        </div>

        <h3>Keluar dari akun?</h3>
        <p>Apakah kamu yakin ingin keluar dari akun administrator?</p>

        <div class="admin-logout-actions">
            <button type="button" id="admin-cancel-logout">
                Batal
            </button>

            <button type="button" id="admin-confirm-logout">
                Ya, Keluar
            </button>
        </div>

    </div>
</div>
