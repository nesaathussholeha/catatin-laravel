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
            <p>Kelola seluruh catatan yang diunggah oleh pengguna CatatIn.</p>
        </div>

        <button type="button" class="catatan-add-button" id="open-add-modal">
            + Tambah Catatan
        </button>
    </div>

    @if (session('sukses'))
        <div class="catatan-alert success">
            {{ session('sukses') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="catatan-alert error">
            <strong>Data belum berhasil disimpan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="catatan-card">
        <div class="catatan-toolbar">
            <input
                type="text"
                id="search-input"
                placeholder="Cari judul, penulis, atau jurusan..."
            >
        </div>

        <div class="catatan-table-wrapper">
            <table class="catatan-table" id="catatan-table">
                <thead>
                    <tr>
                        <th>Judul Catatan</th>
                        <th>Penulis</th>
                        <th>Jurusan</th>
                        <th>Upvote</th>
                        <th>Status</th>
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
                                <small>{{ $item->matkul }}</small>
                            </td>

                            <td>
                                {{ $item->anonim ? 'Anonim' : ($item->user->name ?? 'Pengguna tidak ditemukan') }}
                            </td>

                            <td>{{ $item->jurusan }}</td>

                            <td>{{ $item->upvote }}</td>

                            <td>
                                <span class="status-badge status-{{ strtolower($item->status) }}">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td>
                                <div class="catatan-actions">

                                    <button
                                        type="button"
                                        class="catatan-action-button view-button"
                                        data-judul="{{ $item->judul }}"
                                        data-penulis="{{ $item->anonim ? 'Anonim' : ($item->user->name ?? 'Pengguna tidak ditemukan') }}"
                                        data-jurusan="{{ $item->jurusan }}"
                                        data-matkul="{{ $item->matkul }}"
                                        data-kategori="{{ $item->category->name ?? '-' }}"
                                        data-deskripsi="{{ $item->deskripsi }}"
                                        data-tautan="{{ $item->tautan }}"
                                        data-isi="{{ $item->deskripsi }}"
                                        data-status="{{ $item->status }}"
                                    >
                                        Lihat
                                    </button>

                                    <button
                                        type="button"
                                        class="catatan-action-button edit-button"
                                        data-id="{{ $item->id }}"
                                        data-user="{{ $item->user_id }}"
                                        data-judul="{{ $item->judul }}"
                                        data-matkul="{{ $item->matkul }}"
                                        data-jurusan="{{ $item->jurusan }}"
                                        data-category="{{ $item->category_id }}"
                                        data-deskripsi="{{ $item->deskripsi }}"
                                        data-tautan="{{ $item->tautan }}"
                                        data-visibilitas="{{ $item->visibilitas }}"
                                        data-anonim="{{ $item->anonim ? 1 : 0 }}"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        action="{{ route('admin.kelola-catatan.status', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="status"
                                            value="{{ $item->status === 'Terbit' ? 'Draft' : 'Terbit' }}"
                                        >

                                        <button type="submit" class="catatan-action-button">
                                            {{ $item->status === 'Terbit' ? 'Jadikan Draft' : 'Terbitkan' }}
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.kelola-catatan.destroy', $item->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus catatan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="catatan-action-button delete-button">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-row">
                                Belum ada catatan yang tersedia.
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

{{-- MODAL TAMBAH CATATAN --}}
<div class="catatan-modal-overlay" id="add-modal">
    <div class="catatan-modal">
        <div class="catatan-modal-header">
            <h2>Tambah Catatan</h2>
            <button type="button" class="modal-close" data-close="add-modal">&times;</button>
        </div>

        <form action="{{ route('admin.kelola-catatan.store') }}" method="POST">
            @csrf

            <div class="catatan-form-group">
                <label>Penulis</label>
                <select name="user_id" required>
                    <option value="">Pilih penulis</option>
                    @foreach ($penulis as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="catatan-form-group">
                <label>Judul Catatan</label>
                <input type="text" name="judul" maxlength="150" required>
            </div>

            <div class="catatan-form-group">
                <label>Mata Kuliah</label>
                <input type="text" name="matkul" maxlength="100" required>
            </div>

            <div class="catatan-form-group">
    <label>Jurusan</label>
    <input
        type="text"
        name="jurusan"
        maxlength="50"
        placeholder="Masukkan nama jurusan"
        required
    >
</div>

            <div class="catatan-form-group">
                <label>Kategori</label>
                <select name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="catatan-form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" maxlength="255" required></textarea>
            </div>

            <div class="catatan-form-group">
                <label>Link Catatan</label>
                <input type="url" name="tautan" maxlength="500" placeholder="https://..." required>
            </div>

            <div class="catatan-form-group">
                <label>Visibilitas</label>
                <select name="visibilitas" required>
                    <option value="publik">Publik</option>
                    <option value="pribadi">Pribadi</option>
                </select>
            </div>

            <div class="catatan-form-group checkbox-group">
                <label>
                    <input type="checkbox" name="anonim" value="1">
                    Tampilkan sebagai anonim
                </label>
            </div>

            <button type="submit" class="catatan-save-button">
                Simpan Catatan
            </button>
        </form>
    </div>
</div>

{{-- MODAL EDIT CATATAN --}}
<div class="catatan-modal-overlay" id="edit-modal">
    <div class="catatan-modal">
        <div class="catatan-modal-header">
            <h2>Edit Catatan</h2>
            <button type="button" class="modal-close" data-close="edit-modal">&times;</button>
        </div>

        <form id="edit-catatan-form" method="POST">
            @csrf
            @method('PUT')

            <div class="catatan-form-group">
                <label>Penulis</label>
                <select name="user_id" id="edit-user" required>
                    @foreach ($penulis as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="catatan-form-group">
                <label>Judul Catatan</label>
                <input type="text" name="judul" id="edit-judul" maxlength="150" required>
            </div>

            <div class="catatan-form-group">
                <label>Mata Kuliah</label>
                <input type="text" name="matkul" id="edit-matkul" maxlength="100" required>
            </div>

            <div class="catatan-form-group">
    <label>Jurusan</label>
    <input
        type="text"
        name="jurusan"
        id="edit-jurusan"
        maxlength="50"
        placeholder="Masukkan nama jurusan"
        required
    >
</div>

            <div class="catatan-form-group">
                <label>Kategori</label>
                <select name="category_id" id="edit-category" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="catatan-form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" maxlength="255" required></textarea>
            </div>

            <div class="catatan-form-group">
                <label>Link Catatan</label>
                <input type="url" name="tautan" id="edit-tautan" maxlength="500" required>
            </div>

            <div class="catatan-form-group">
                <label>Visibilitas</label>
                <select name="visibilitas" id="edit-visibilitas" required>
                    <option value="publik">Publik</option>
                    <option value="pribadi">Pribadi</option>
                </select>
            </div>

            <div class="catatan-form-group checkbox-group">
                <label>
                    <input type="checkbox" name="anonim" id="edit-anonim" value="1">
                    Tampilkan sebagai anonim
                </label>
            </div>

            <button type="submit" class="catatan-save-button">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

{{-- MODAL LIHAT CATATAN --}}
<div class="catatan-modal-overlay" id="view-modal">
    <div class="catatan-modal">
        <div class="catatan-modal-header">
            <h2>Detail Catatan</h2>
            <button type="button" class="modal-close" data-close="view-modal">&times;</button>
        </div>

        <div class="catatan-detail">
            <p><strong>Judul:</strong> <span id="view-judul"></span></p>
            <p><strong>Penulis:</strong> <span id="view-penulis"></span></p>
            <p><strong>Jurusan:</strong> <span id="view-jurusan"></span></p>
            <p><strong>Mata Kuliah:</strong> <span id="view-matkul"></span></p>
            <p><strong>Kategori:</strong> <span id="view-kategori"></span></p>
            <p><strong>Status:</strong> <span id="view-status"></span></p>
            <p><strong>Deskripsi:</strong></p>
            <div id="view-deskripsi"></div>
            <p><strong>Link Catatan:</strong>
                <a id="view-tautan" href="#" target="_blank" rel="noopener noreferrer">Buka catatan</a>
            </p>
        </div>
    </div>
</div>
@endsection
