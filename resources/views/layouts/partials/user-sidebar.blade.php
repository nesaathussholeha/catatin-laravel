<aside class="sidebar" id="sidebar">

    <div class="sidebar-top">

        {{-- Logo --}}
        <div class="sidebar-header">
            <a href="{{ route('user.dashboard') }}" class="brand">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <span>CatatIn</span>
            </a>

            <button type="button" class="close-sidebar-btn" id="closeSidebar" aria-label="Tutup menu">
                <iconify-icon icon="lucide:x"></iconify-icon>
            </button>
        </div>

        {{-- Navigasi --}}
        <nav class="sidebar-nav">

            <a href="{{ route('user.dashboard') }}"
                class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <iconify-icon icon="akar-icons:dashboard"></iconify-icon>
                <span>Dashboard</span>
            </a>

            <a href="#" class="nav-link">
                <iconify-icon icon="reicon:document-upload"></iconify-icon>
                <span>Unggah Catatan</span>
            </a>

            <a href="#" class="nav-link">
                <iconify-icon icon="hugeicons:note"></iconify-icon>
                <span>Catatan Saya</span>
            </a>

            <a href="{{ route('user.playlists.index') }}"
                class="nav-link {{ request()->routeIs('user.playlists.*') ? 'active' : '' }}">
                <iconify-icon icon="codicon:folder-opened-compact"></iconify-icon>
                <span>Koleksi</span>
            </a>

            <a href="#" class="nav-link">
                <iconify-icon icon="hugeicons:sticky-note-02"></iconify-icon>
                <span>Tugas</span>
            </a>

            <a href="#" class="nav-link">
                <iconify-icon icon="iconamoon:profile-circle"></iconify-icon>
                <span>Profil</span>
            </a>

        </nav>

    </div>

    {{-- Logout --}}
    <div class="sidebar-bottom">
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf

            <button type="button" class="logout-btn" id="logout-trigger">
                <iconify-icon icon="lucide:log-out"></iconify-icon>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</aside>
