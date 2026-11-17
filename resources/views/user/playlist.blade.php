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

    @if (session('success'))
        <p class="pl-flash">{{ session('success') }}</p>
    @endif

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
                              onsubmit="return confirm('Hapus playlist ini?')">
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

<div class="pl-modal" id="plModal" hidden>

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
@endsection