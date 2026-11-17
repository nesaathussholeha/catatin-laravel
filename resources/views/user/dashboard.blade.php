@extends('layouts.user')
@section('title', 'Jelajahi Catatan')
@section('page-title', 'Jelajahi Catatan')

@push('styles')
    @vite('resources/css/dashboard-user.css')
@endpush

@push('scripts')
    @vite('resources/js/dashboard-user.js')
@endpush

@section('content')
<div class="dashboard-user">

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
                    <button type="button" class="upvote" data-dasar="{{ (int) $n->upvote }}" aria-pressed="false">
                        <span aria-hidden="true">▲</span>
                        <span class="jumlah">{{ (int) $n->upvote }}</span> upvote
                    </button>

                    {{-- Ganti "#" dengan route detail catatan setelah halamannya ada --}}
                    <a class="detail" href="#">Lihat detail</a>
                </div>
            </article>
        @endforeach
    </div>

    <div class="kosong" id="kosong" hidden>
        <p><strong>Tidak ada catatan yang cocok.</strong></p>
        <p>Coba kata kunci lain atau ubah filter.</p>
        <button type="button" class="tombol" id="reset">Reset pencarian</button>
    </div>

</div>
@endsection