@extends('layouts.admin')

@section('title', 'Kelola Catatan - CatatIn')

@push('styles')
    @vite('resources/css/kelola-catatan.css')
@endpush

@push('scripts')
    @vite('resources/js/kelola-catatan.js')
@endpush

@section('content')
<div class="catatan-admin-page">

    <div class="catatan-page-header">
        <div>
            <h1>Kelola Catatan</h1>
            <p>Pantau dan kelola catatan yang dibagikan mahasiswa.</p>
        </div>
    </div>

    @if (session('sukses'))
        <div class="catatan-alert success">
            {{ session('sukses') }}
        </div>
    @endif

    <div class="catatan-statistics">
        <div class="catatan-stat-card">
            <span>Total Catatan Aktif</span>
            <strong>{{ $jumlahAktif }}</strong>
        </div>

        <div class="catatan-stat-card">
            <span>Dihapus Sementara</span>
            <strong>{{ $jumlahDihapus }}</strong>
        </div>
    </div>

    <div class="catatan-card">

        <div class="catatan-tabs">
            <a
                href="{{ route('admin.kelola-catatan.index', ['filter' => 'aktif']) }}"
                class="{{ $filter === 'aktif' ? 'active' : '' }}"
            >
                Semua Catatan
            </a>

            <a
                href="{{ route('admin.kelola-catatan.index', ['filter' => 'dihapus']) }}"
                class="{{ $filter === 'dihapus' ? 'active' : '' }}"
            >
                Dihapus Sementara
            </a>
        </div>

        <form
            method="GET"
            action="{{ route('admin.kelola-catatan.index') }}"
            class="catatan-toolbar"
        >
            <input type="hidden" name="filter" value="{{ $filter }}">

            <input
                type="text"
                name="cari"
                value="{{ request('cari') }}"
                placeholder="Cari judul, penulis, mata kuliah, atau jurusan..."
            >

            <button type="submit">Cari</button>
        </form>

        <div class="catatan-table-wrapper">
            <table class="catatan-table">

                <thead>
                    <tr>
                        <th>Judul Catatan</th>
                        <th>Penulis</th>
                        <th>Mata Kuliah</th>
                        <th>Jurusan</th>
                        <th>Kategori</th>

                        @if ($filter === 'dihapus')
                            <th>Dihapus Pada</th>
                        @else
                            <th>Status</th>
                        @endif

                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($catatan as $item)
                        <tr>
                            <td>
                                <div class="catatan-title">
                                    {{ $item->judul }}
                                </div>
                                <small>{{ $item->deskripsi }}</small>
                            </td>

                            <td>
                                {{ $item->anonim ? 'Anonim' : ($item->user->name ?? 'Pengguna tidak ditemukan') }}
                            </td>

                            <td>{{ $item->matkul }}</td>

                            <td>{{ $item->jurusan }}</td>

                            <td>{{ $item->category->name ?? '-' }}</td>

                            @if ($filter === 'dihapus')
                                <td>
                                    {{ $item->deleted_at?->format('d-m-Y H:i') ?? '-' }}
                                </td>
                            @else
                                <td>
                                    <form
                                        action="{{ route('admin.kelola-catatan.update-status', $item->id) }}"
                                        method="POST"
                                        class="status-form"
                                        onsubmit="return confirm('Apakah kamu yakin ingin mengubah status catatan ini?')"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="status"
                                            class="status-select"
                                            onchange="this.form.requestSubmit()"
                                        >
                                            <option
                                                value="Terbit"
                                                {{ $item->status === 'Terbit' ? 'selected' : '' }}
                                            >
                                                Terbit
                                            </option>

                                            <option
                                                value="Draft"
                                                {{ $item->status === 'Draft' ? 'selected' : '' }}
                                            >
                                                Draft
                                            </option>
                                        </select>
                                    </form>
                                </td>
                            @endif

                            <td>
                                <div class="catatan-actions">

                                    <a
                                        href="{{ route('admin.kelola-catatan.show', $item->id) }}"
                                        class="catatan-action-button view-button"
                                    >
                                        Detail
                                    </a>

                                    @if ($filter === 'dihapus')

                                        <form
                                            action="{{ route('admin.kelola-catatan.restore', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pulihkan catatan ini?')"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="catatan-action-button">
                                                Pulihkan
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('admin.kelola-catatan.force-delete', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Catatan akan dihapus permanen dan tidak dapat dipulihkan. Lanjutkan?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="catatan-action-button delete-button">
                                                Hapus Permanen
                                            </button>
                                        </form>

                                    @endif

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">
                                @if ($filter === 'dihapus')
                                    Tidak ada catatan yang dihapus sementara.
                                @else
                                    Belum ada catatan yang tersedia.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="catatan-pagination">
            {{ $catatan->links() }}
        </div>

    </div>
</div>
@endsection
