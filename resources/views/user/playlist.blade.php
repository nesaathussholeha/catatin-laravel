@extends('layouts.user')

@section('title', 'Koleksi')
@section('page-title', 'Koleksi belajar saya')

@push('styles')
    @vite('resources/css/playlist.css')
@endpush

@push('scripts')
    @vite('resources/js/playlist.js')
@endpush

@section('content')

    {{-- Notifikasi --}}
    @if (session('success') || session('error'))
        <div class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}" id="toastNotification">
            <iconify-icon icon="{{ session('success') ? 'lucide:check-circle' : 'lucide:alert-circle' }}"></iconify-icon>
            <span>{{ session('success') ?? session('error') }}</span>
            <button type="button" class="toast-close" id="btnTutupToast">&times;</button>
        </div>
    @endif

    @php
        $errCreate = $errors->any() && old('_form') === 'create';
        $errEdit   = $errors->any() && old('_form') === 'edit';
    @endphp

<div class="pl-layout">

    {{-- KIRI: daftar folder --}}
    <aside class="pl-sidebar">

        @forelse ($playlists as $p)
            <div class="pl-folder {{ $aktif && $aktif->id === $p->id ? 'aktif' : '' }}">

                <a class="pl-folder-link"
                   href="{{ route('user.playlists.index', ['playlist' => $p->id]) }}">
                    <strong>{{ $p->name }}</strong>
                    {{-- Jumlah asli menyusul setelah tabel Catatan ada --}}
                    <span>0 catatan</span>
                </a>

                <div class="pl-menu">
                    <button type="button" class="pl-menu-btn" aria-expanded="false"
                            aria-label="Menu folder {{ $p->name }}">&#8942;</button>

                    <div class="pl-menu-list" hidden>
                        <button type="button" class="pl-edit"
                                data-url="{{ route('user.playlists.update', $p) }}"
                                data-id="{{ $p->id }}"
                                data-name="{{ $p->name }}"
                                data-description="{{ $p->description }}">
                            Ubah
                        </button>

                        <form method="POST"
                              action="{{ route('user.playlists.destroy', $p) }}"
                              class="pl-delete-form"
                              data-nama="{{ $p->name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pl-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="pl-empty">Belum ada folder. Buat yang pertama!</p>
        @endforelse

        <div class="pl-sidebar-aksi">
            <button type="button" class="pl-btn" id="plOpenModal">+ Buat folder</button>
        </div>

    </aside>

        {{-- KANAN: isi folder --}}
    <section class="pl-detail">

        @if ($aktif)

            <div class="pl-detail-head">
                <div>
                    <h2>Isi Folder: {{ $aktif->name }}</h2>
                    @if ($aktif->description)
                        <p class="pl-desc">{{ $aktif->description }}</p>
                    @endif
                </div>
            </div>

            <div class="pl-table-wrap">
                <table class="pl-table">
                    <thead>
                        <tr>
                            <th>Judul catatan</th>
                            <th>Jurusan</th>
                            <th class="pl-aksi-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- NANTI diganti @forelse catatan asli setelah tabel Catatan ada --}}
                        <tr>
                            <td colspan="3" class="pl-kosong">Belum ada catatan di folder ini.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pl-detail-foot">
                <a class="pl-btn-katalog" href="{{ route('user.dashboard') }}">
                    <iconify-icon icon="lucide:plus-circle"></iconify-icon>
                    Tambahkan Catatan dari katalog
                </a>
            </div>

        @else

            <div class="pl-placeholder">
                <iconify-icon icon="lucide:folder-open"></iconify-icon>
                <p>Pilih atau buat folder untuk melihat isinya.</p>
            </div>

        @endif

    </section>
</div>

{{-- MODAL: Buat folder --}}
<div class="pl-modal" id="plModal" @unless ($errCreate) hidden @endunless>
    <form method="POST" action="{{ route('user.playlists.store') }}" class="pl-modal-box" novalidate>
        @csrf
        <input type="hidden" name="_form" value="create">

        <h2>Buat Folder Baru</h2>

        <label for="plNama">Nama folder</label>
        <input type="text" id="plNama" name="name" maxlength="60"
               value="{{ $errCreate ? old('name') : '' }}" required>
        @if ($errCreate) @error('name') <small class="pl-error">{{ $message }}</small> @enderror @endif

        <label for="plDeskripsi">Deskripsi</label>
        <textarea id="plDeskripsi" name="description" rows="3" maxlength="150">{{ $errCreate ? old('description') : '' }}</textarea>
        @if ($errCreate) @error('description') <small class="pl-error">{{ $message }}</small> @enderror @endif

        <div class="pl-modal-actions">
            <button type="button" class="pl-btn-light" id="plCloseModal">Batal</button>
            <button type="submit" class="pl-btn">Simpan</button>
        </div>
    </form>
</div>

{{-- MODAL: Ubah folder --}}
<div class="pl-modal" id="plEditModal" @unless ($errEdit) hidden @endunless>
    <form method="POST" id="plEditForm" class="pl-modal-box" novalidate
          action="{{ $errEdit ? route('user.playlists.update', (int) old('edit_id')) : '#' }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="_form" value="edit">
        <input type="hidden" name="edit_id" id="plEditId" value="{{ $errEdit ? old('edit_id') : '' }}">

        <h2>Ubah Folder</h2>

        <label for="plEditNama">Nama folder</label>
        <input type="text" id="plEditNama" name="name" maxlength="60"
               value="{{ $errEdit ? old('name') : '' }}" required>
        @if ($errEdit) @error('name') <small class="pl-error">{{ $message }}</small> @enderror @endif

        <label for="plEditDeskripsi">Deskripsi</label>
        <textarea id="plEditDeskripsi" name="description" rows="3" maxlength="150">{{ $errEdit ? old('description') : '' }}</textarea>
        @if ($errEdit) @error('description') <small class="pl-error">{{ $message }}</small> @enderror @endif

        <div class="pl-modal-actions">
            <button type="button" class="pl-btn-light" id="plEditClose">Batal</button>
            <button type="submit" class="pl-btn">Simpan Perubahan</button>
        </div>
    </form>
</div>

{{-- MODAL: Konfirmasi hapus --}}
<div class="pl-modal" id="plConfirm" hidden>
    <div class="pl-modal-box pl-confirm">
        <h2>Hapus folder?</h2>
        <p>
            Folder <strong id="plConfirmNama"></strong> akan dihapus.
            Catatan di dalamnya tidak ikut terhapus.
        </p>

        <div class="pl-modal-actions">
            <button type="button" class="pl-btn-light" id="plConfirmBatal">Batal</button>
            <button type="button" class="pl-btn pl-btn-danger" id="plConfirmYa">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection