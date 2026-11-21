
@extends('layouts.admin')

@section('title', 'Detail Laporan Pengguna')
@section('page-heading', 'Kelola Pengguna')

@push('styles')
    @vite([
        'resources/css/user-reports-detail.css',
        'resources/js/user-reports-detail.js'
    ])
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Detail Laporan Pengguna</h1>
            <p>Periksa detail laporan dan tentukan keputusan terhadap laporan mahasiswa.</p>
        </div>

        <a href="{{ route('admin.user-reports.index') }}" class="btn-kembali">
            Kembali
        </a>
    </div>

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

    {{-- Informasi Laporan --}}
    <div class="card">
        <div class="card-header">
            <h3>Informasi Laporan</h3>
        </div>

        <div class="report-information">
            <div class="detail-item">
                <span>ID Laporan</span>
                <strong>#{{ $report->id }}</strong>
            </div>

            <div class="detail-item">
                <span>Nama Pelapor</span>
                <strong>{{ $report->reporter->name ?? '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Pengguna Dilaporkan</span>
                <strong>{{ $report->reportedUser->name ?? '-' }}</strong>
            </div>

            <div class="detail-item">
                <span>Alasan Laporan</span>
                <strong>{{ $report->reason }}</strong>
            </div>

            <div class="detail-item">
                <span>Deskripsi</span>
                <p>{{ $report->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
            </div>

            <div class="detail-item">
                <span>Status Laporan</span>

                @if ($report->status === 'pending')
                    <span class="badge badge-warning">Menunggu</span>
                @else
                    <span class="badge badge-success">Selesai</span>
                @endif
            </div>

            @if ($report->status === 'completed')
                <div class="detail-item">
                    <span>Keputusan Admin</span>
                    <strong>
                        {{ $report->decision === 'accepted' ? 'Diterima' : 'Ditolak' }}
                    </strong>
                </div>

                <div class="detail-item">
                    <span>Admin Pemeriksa</span>
                    <strong>{{ $report->reviewer->name ?? '-' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Waktu Pemeriksaan</span>
                    <strong>{{ $report->reviewed_at?->format('d M Y, H:i') ?? '-' }}</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- Keputusan Admin --}}
    @if ($report->status === 'pending')
        <div class="card decision-card">
            <div class="card-header">
                <h3>Keputusan Admin</h3>
            </div>

            <form action="{{ route('admin.user-reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="decision">Keputusan</label>

                    <select name="decision" id="decision" required>
                        <option value="">Pilih Keputusan</option>

                        <option value="accepted" {{ old('decision') === 'accepted' ? 'selected' : '' }}>
                            Terima Laporan
                        </option>

                        <option value="rejected" {{ old('decision') === 'rejected' ? 'selected' : '' }}>
                            Tolak Laporan
                        </option>
                    </select>

                    @error('decision')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group" id="accountStatusGroup">
                    <label for="account_status">Tindakan terhadap Akun</label>

                    <select name="account_status" id="account_status">
                        <option value="">Pilih Tindakan</option>

                        <option value="inactive" {{ old('account_status') === 'inactive' ? 'selected' : '' }}>
                            Nonaktifkan Akun
                        </option>

                        <option value="blocked" {{ old('account_status') === 'blocked' ? 'selected' : '' }}>
                            Blokir Akun
                        </option>
                    </select>

                    @error('account_status')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn-submit" id="btnSimpanKeputusan">
                    Simpan Keputusan
                </button>
            </form>
        </div>
    @endif
@endsection
