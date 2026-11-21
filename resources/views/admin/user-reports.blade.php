
@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-heading', 'Kelola Pengguna')

@push('styles')
    @vite(['resources/css/user-reports.css', 'resources/js/user-reports.js'])
@endpush

@section('content')
    {{-- <div class="page-header">
        <div>
            <h1>Kelola Laporan Pengguna</h1>
            <p>Kelola laporan mahasiswa terhadap pengguna lain.</p>
        </div>
    </div> --}}

    {{-- Notifikasi --}}
    @if (session('success') || session('error'))
        <div class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}" id="toastNotification">
            <iconify-icon icon="{{ session('success') ? 'lucide:check-circle' : 'lucide:alert-circle' }}"></iconify-icon>

            <span>{{ session('success') ?? session('error') }}</span>

            <button type="button" class="toast-close" id="btnTutupToast">
                &times;
            </button>
        </div>
    @endif

    {{-- Ringkasan Laporan --}}
    {{-- <div class="summary-container">
        <div class="summary-card">
            <h3>Total Laporan</h3>
            <p>{{ $totalReports }}</p>
        </div>

        <div class="summary-card">
            <h3>Menunggu</h3>
            <p>{{ $pendingReports }}</p>
        </div>

        <div class="summary-card">
            <h3>Selesai</h3>
            <p>{{ $completedReports }}</p>
        </div>
    </div> --}}

    {{-- Pencarian dan Filter --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Laporan</h3>
        </div>

        <form action="{{ route('admin.user-reports.index') }}" method="GET" class="filter-form">
            <input
                type="text"
                name="search"
                placeholder="Cari nama atau alasan laporan..."
                value="{{ request('search') }}"
            >

            <select name="status">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                    Menunggu
                </option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>

            <button type="submit">Cari</button>

            <a href="{{ route('admin.user-reports.index') }}">Reset</a>
        </form>

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
                        <tr>
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
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
