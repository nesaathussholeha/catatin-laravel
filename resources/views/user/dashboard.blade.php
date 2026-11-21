@extends('layouts.user')
@section('title', 'Dashboard')
@section('page-title', 'Jelajahi Catatan')

@push('styles')
    @vite('resources/css/dashboard-user.css')
@endpush

@push('scripts')
    @vite('resources/js/dashboard-user.js')
@endpush

@section('content')

    {{-- Notifikasi --}}
    @php
        $pesanToast = session('success') ?? session('error') ?? ($errors->any() ? $errors->first() : null);
        $toastError = ! session('success');
    @endphp
    @if ($pesanToast)
        <div class="toast-notification {{ $toastError ? 'toast-error' : 'toast-success' }}" id="toastNotification">
            <iconify-icon icon="{{ $toastError ? 'lucide:alert-circle' : 'lucide:check-circle' }}"></iconify-icon>
            <span>{{ $pesanToast }}</span>
            <button type="button" class="toast-close" id="btnTutupToast">&times;</button>
        </div>
    @endif

<div class="dashboard-user">

    <div class="sapaan">
        <h2>Halo, {{ auth()->user()->name }}! 👋</h2>
        <p>Temukan catatan kuliah yang kamu butuhkan hari ini.</p>
    </div>

    <form class="bar-cari" role="search" id="form-cari">
        <label class="kolom-cari">
            <span aria-hidden="true">🔍</span>
            <span class="sr-only">Cari catatan</span>
            <input type="search" id="cari" placeholder="Cari judul, matkul, atau penyusun...">
        </label>

        <label class="pilih-urut">
            <span class="sr-only">Urutkan</span>
            <select id="urut">
                <option value="terbaru">↓ Terbaru</option>
                <option value="populer">↓ Terpopuler</option>
            </select>
        </label>
    </form>

    <div class="chip-baris" aria-label="Filter catatan">
        <label class="pilih-jurusan">
            <span class="sr-only">Jurusan</span>
            <select id="jurusan">
                <option value="">Semua jurusan</option>
                @foreach ($daftarJurusan as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>
        </label>

        <button type="button" class="chip aktif" data-kategori="semua" aria-pressed="true">Semua</button>

        @foreach ($daftarKategori as $k)
            <button type="button" class="chip" data-kategori="{{ $k }}" aria-pressed="false">{{ $k }}</button>
        @endforeach
    </div>

    <div class="grid-kartu" id="grid">
        @foreach ($notes as $n)
            <article class="kartu"
                data-jurusan="{{ $n->jurusan }}"
                data-kategori="{{ $n->tipe }}"
                data-tanggal="{{ $n->tanggal }}"
                data-cari="{{ mb_strtolower($n->judul.' '.$n->matkul.' '.$n->penulis) }}">

                <span class="lencana">{{ $n->jurusan }}</span>
                <h2>{{ $n->judul }}</h2>
                <p class="meta">oleh {{ $n->penulis }} · {{ $n->tipe }}</p>

                <div class="kartu-bawah">
                    <button type="button" class="upvote" data-dasar="{{ $n->upvote }}" aria-pressed="false">
                        <span aria-hidden="true">▲</span>
                        <span class="jumlah">{{ $n->upvote }}</span> upvote
                    </button>

                    <button type="button" class="pd-tambah"
                            data-note-id="{{ $n->id }}" data-note-title="{{ $n->judul }}">
                        + Playlist
                    </button>

                    <a class="detail" href="{{ route('catatan.show', $n->id) }}">
                        Lihat detail
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="kosong" id="kosong" @if ($notes->isNotEmpty()) hidden @endif>
        <p><strong>Tidak ada catatan yang cocok.</strong></p>
        <p>Coba kata kunci lain atau ubah filter.</p>
        <button type="button" class="tombol" id="reset">Reset pencarian</button>
    </div>

</div>

{{-- Modal: simpan ke playlist --}}
<div class="pd-modal" id="pdModal" hidden>
    <form method="POST" action="{{ route('user.playlists.attach') }}" class="pd-box">
        @csrf
        <input type="hidden" name="catatan_id" id="pdCatatanId">

        <h3>Simpan ke Playlist</h3>
        <p class="pd-judul" id="pdJudul"></p>

        @if ($playlists->isEmpty())
            <p class="pd-info">Kamu belum punya folder.
                <a href="{{ route('user.playlists.index') }}">Buat di Koleksi</a></p>
        @else
            <label for="pdPlaylist">Pilih folder</label>
            <select name="playlist_id" id="pdPlaylist" required>
                @foreach ($playlists as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        @endif

        <div class="pd-aksi">
            <button type="button" id="pdBatal">Batal</button>
            @if ($playlists->isNotEmpty())
                <button type="submit" class="pd-simpan">Simpan</button>
            @endif
        </div>
    </form>
</div>
@endsection