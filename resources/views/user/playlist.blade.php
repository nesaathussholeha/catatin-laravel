@extends('layouts.user')

@section('title', 'Playlist')
@section('page-title', 'Playlist Saya')

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

<div class="pl-page">

    <div class="pl-header">
        <div>
            <h1>Playlist Saya</h1>
            <p>Kelompokkan catatan favoritmu agar mudah ditemukan.</p>
        </div>

        <button type="button" class="pl-btn" id="plOpenModal">
            + Buat Playlist
        </button>
    </div>

    @if ($playlists->isEmpty())

        <p class="pl-empty">Belum ada playlist. Buat yang pertama!</p>

    @else

        <div class="pl-grid">
            @foreach ($playlists as $p)

                <article class="pl-card">
                    <h3>{{ $p->name }}</h3>
                    <p>{{ $p->description ?: 'Tanpa deskripsi' }}</p>

                    <div class="pl-footer">
                        {{-- Jumlah catatan menyusul setelah tabel catatan ada --}}
                        <span>0 catatan</span>
                        <form method="POST"
                            action="{{ route('user.playlists.destroy', $p) }}"
                            class="pl-delete-form"
                            data-nama="{{ $p->name }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="pl-delete">Hapus</button>
                        </form>
                    </div>
                </article>

            @endforeach
        </div>

    @endif

</div>

<div class="pl-modal" id="plModal" @unless ($errors->any()) hidden @endunless>

    <form method="POST"
          action="{{ route('user.playlists.store') }}"
          class="pl-modal-box">
        @csrf

        <h2>Buat Playlist Baru</h2>

        <label for="plNama">Nama playlist</label>
        <input type="text" id="plNama" name="name" required maxlength="60" value="{{ old('name') }}">
        @error('name')
            <small class="pl-error">{{ $message }}</small>
        @enderror

        <label for="plDeskripsi">Deskripsi</label>
        <textarea id="plDeskripsi" name="description" rows="3" maxlength="150">{{ old('description') }}</textarea>

        <div class="pl-modal-actions">
            <button type="button" class="pl-btn-light" id="plCloseModal">Batal</button>
            <button type="submit" class="pl-btn">Simpan</button>
        </div>
    </form>

</div>

<div class="pl-modal" id="plConfirm" hidden>
    <div class="pl-modal-box pl-confirm">
        <h2>Hapus playlist?</h2>
        <p>
            Playlist <strong id="plConfirmNama"></strong> akan dihapus.
            Catatan di dalamnya tidak ikut terhapus.
        </p>

        <div class="pl-modal-actions">
            <button type="button" class="pl-btn-light" id="plConfirmBatal">Batal</button>
            <button type="button" class="pl-btn pl-btn-danger" id="plConfirmYa">Ya, Hapus</button>
        </div>
    </div>
</div>
@endsection