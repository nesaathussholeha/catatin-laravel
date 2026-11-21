
@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-heading', 'Kelola Pengguna')

@push('styles')
    @vite(['resources/css/user-reports.css', 'resources/js/user-reports.js'])
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Kelola Pengguna</h1>
            <p>Kelola akun mahasiswa dan laporan pengguna.</p>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success') || session('error'))
        <div class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}" id="toastNotification">
            <iconify-icon icon="{{ session('success') ? 'lucide:check-circle' : 'lucide:alert-circle' }}"></iconify-icon>
            <span>{{ session('success') ?? session('error') }}</span>
            <button type="button" class="toast-close" id="btnTutupToast">&times;</button>
        </div>
    @endif

    {{-- Tab Navigasi --}}
    <div class="user-tabs">
        <button type="button" class="user-tab active" data-tab="daftar-pengguna">
            Daftar Pengguna
        </button>

        <button type="button" class="user-tab" data-tab="laporan-pengguna">
            Laporan Pengguna
        </button>
    </div>

    {{-- TAB DAFTAR PENGGUNA --}}
    <div class="user-tab-content active" id="daftar-pengguna">
        <div class="card">
            <div class="card-header">
                <h3>Daftar Pengguna Mahasiswa</h3>
            </div>

            {{-- Pencarian dan Filter Pengguna --}}
            <form action="{{ route('admin.user-reports.index') }}" method="GET" class="filter-form">
                <input type="text" name="user_search" id="searchPengguna"
                    placeholder="Cari nama, username, atau email..."
                    value="{{ request('user_search') }}">

                <select name="user_status" id="filterStatusPengguna">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('user_status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('user_status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="blocked" {{ request('user_status') === 'blocked' ? 'selected' : '' }}>Diblokir</option>
                </select>

                <button type="submit">Cari</button>
                <a href="{{ route('admin.user-reports.index') }}">Reset</a>
            </form>

            {{-- Tabel Pengguna --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengguna</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr class="baris-pengguna" data-status="{{ $user->status }}">
                                <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username ?? '-' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->status === 'active')
                                        <span class="badge badge-success">Aktif</span>
                                    @elseif ($user->status === 'inactive')
                                        <span class="badge badge-warning">Tidak Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Diblokir</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.update-status', $user->id) }}"
                                        method="POST" class="status-form">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status" class="status-select"
                                            data-current-status="{{ $user->status }}"
                                            data-user-name="{{ $user->name }}">
                                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                            <option value="blocked" {{ $user->status === 'blocked' ? 'selected' : '' }}>Diblokir</option>
                                        </select>

                                        <button type="button" class="btn-update-status">Ubah</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada pengguna mahasiswa.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="hasilPenggunaTidakDitemukan" style="display: none;">
                            <td colspan="6" class="text-center">
                                Pengguna tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- TAB LAPORAN PENGGUNA --}}
    <div class="user-tab-content" id="laporan-pengguna">
        <div class="card">
            <div class="card-header">
                <h3>Daftar Laporan</h3>
            </div>

            {{-- Pencarian dan Filter Laporan --}}
            <form action="{{ route('admin.user-reports.index') }}" method="GET" class="filter-form">
                <input type="text" name="search" id="searchLaporan"
                    placeholder="Cari nama atau alasan laporan..."
                    value="{{ request('search') }}">

                <select name="status" id="filterStatusLaporan">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button type="submit">Cari</button>
                <a href="{{ route('admin.user-reports.index') }}">Reset</a>
            </form>

            {{-- Tabel Laporan --}}
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pelapor</th>
                            <th>Pengguna Dilaporkan</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($reports as $report)
                            <tr class="baris-laporan" data-status="{{ $report->status }}">
                                <td>{{ $loop->iteration + $reports->firstItem() - 1 }}</td>
                                <td>{{ $report->reporter->name ?? '-' }}</td>
                                <td>{{ $report->reportedUser->name ?? '-' }}</td>
                                <td>{{ $report->reason }}</td>
                                <td>
                                    @if ($report->status === 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @else
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.user-reports.show', $report->id) }}">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada laporan pengguna.
                                </td>
                            </tr>
                        @endforelse

                        <tr id="hasilLaporanTidakDitemukan" style="display: none;">
                            <td colspan="6" class="text-center">
                                Laporan tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $reports->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Ubah Status --}}
    <div class="status-modal-overlay" id="statusConfirmModal">
        <div class="status-modal">
            <div class="status-modal-icon">
                <iconify-icon icon="lucide:alert-circle"></iconify-icon>
            </div>

            <h3>Konfirmasi Ubah Status</h3>

            <p id="statusModalMessage">
                Apakah kamu yakin ingin mengubah status pengguna ini?
            </p>

            <div class="status-modal-actions">
                <button type="button" class="btn-modal-cancel" id="btnBatalStatus">
                    Batal
                </button>

                <button type="button" class="btn-modal-confirm" id="btnKonfirmasiStatus">
                    Ya, Ubah Status
                </button>
            </div>
        </div>
    </div>
@endsection
