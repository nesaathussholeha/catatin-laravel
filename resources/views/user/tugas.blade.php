
@extends('layouts.user')

@section('title', 'Pengingat Tugas')
@section('page-title', 'Pengingat Tugas')

@section('content')

<div class="halaman-tugas">

    <div class="tugas-heading">
        <div>
            <h1>Pengingat Tugas</h1>
            <p>Catat dan pantau tugas belajarmu agar tidak terlewat.</p>
        </div>

        <button type="button" class="tombol-tambah" id="buka-modal">
            + Tambah Tugas
        </button>
    </div>

    @if (session('success'))
        <div class="pesan-sukses">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="pesan-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="tugas-filter">
        <form action="{{ route('tugas.index') }}" method="GET">

            <select name="filter" onchange="this.form.submit()">
                <option value="semua"
                    {{ request('filter', 'semua') === 'semua' ? 'selected' : '' }}>
                    Semua Tugas
                </option>

                <option value="belum"
                    {{ request('filter') === 'belum' ? 'selected' : '' }}>
                    Belum Selesai
                </option>

                <option value="selesai"
                    {{ request('filter') === 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>

            <select name="urut" onchange="this.form.submit()">
                <option value="terdekat"
                    {{ request('urut', 'terdekat') === 'terdekat' ? 'selected' : '' }}>
                    Deadline Terdekat
                </option>

                <option value="terjauh"
                    {{ request('urut') === 'terjauh' ? 'selected' : '' }}>
                    Deadline Terjauh
                </option>
            </select>

        </form>
    </div>

    <div class="daftar-tugas">

        @forelse ($tugas as $item)

            <article class="kartu-tugas">

                <div class="tugas-info">

                    <h3 class="{{ $item->status === 'selesai' ? 'teks-selesai' : '' }}">
                        {{ $item->judul }}
                    </h3>

                    <p>
                        Deadline:
                        {{ $item->deadline ? $item->deadline->format('d M Y') : 'Tidak ditentukan' }}
                    </p>

                    @if ($item->status === 'selesai')

                        <span class="status-tugas status-selesai">
                            Selesai
                        </span>

                    @elseif ($item->deadline && $item->deadline->lt(today()))

                        <span class="status-tugas status-terlambat">
                            Terlambat
                        </span>

                    @else

                        <span class="status-tugas status-belum">
                            Belum selesai
                        </span>

                    @endif

                </div>

                <div class="tugas-aksi">

                    {{-- Tombol tandai selesai / batalkan --}}
                    <form action="{{ route('tugas.update', $item) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status"
                            value="{{ $item->status === 'selesai' ? 'belum_selesai' : 'selesai' }}">

                        <button type="submit" class="tombol-status">
                            {{ $item->status === 'selesai' ? 'Batalkan' : 'Tandai Selesai' }}
                        </button>
                    </form>

                    {{-- Tombol edit --}}
                    <button
                        type="button"
                        class="tombol-edit"
                        data-id="{{ $item->id }}"
                        data-judul="{{ $item->judul }}"
                        data-deadline="{{ $item->deadline ? $item->deadline->format('Y-m-d') : '' }}"
                        data-update-url="{{ route('tugas.update', $item) }}">
                        Edit
                    </button>

                    {{-- Tombol hapus --}}
                    <form
                        action="{{ route('tugas.destroy', $item) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="tombol-hapus">
                            Hapus
                        </button>
                    </form>

                </div>

            </article>

        @empty

            <div class="tugas-kosong">
                <h3>Belum ada tugas</h3>
                <p>Tugas yang sesuai dengan filter belum tersedia.</p>
            </div>

        @endforelse

    </div>

</div>


{{-- MODAL TAMBAH DAN EDIT --}}
<div
    class="modal-tugas"
    id="modal-tugas"
    data-open-on-error="{{ $errors->any() ? '1' : '0' }}">

    <div class="modal-konten">

        <div class="modal-heading">
            <h2 id="judul-modal">Tambah Tugas</h2>

            <button type="button" id="tutup-modal">
                &times;
            </button>
        </div>

        <form
            method="POST"
            action="{{ route('tugas.store') }}"
            id="form-tugas">

            @csrf

            <input type="hidden" name="_method" id="method-tugas" value="POST">

            <input type="hidden" name="prioritas" value="sedang">

            <input type="hidden" name="status" value="belum_selesai">

            <input type="hidden" name="id" id="id-tugas">

            <label for="judul-tugas">Nama Tugas</label>

            <input
                type="text"
                name="judul"
                id="judul-tugas"
                placeholder="Masukkan nama tugas"
                value="{{ old('judul') }}"
                maxlength="150"
                required>

            <label for="deadline-tugas">Deadline</label>

            <input
                type="date"
                name="deadline"
                id="deadline-tugas"
                value="{{ old('deadline') }}">

            <div class="modal-aksi">

                <button type="button" id="batal-modal">
                    Batal
                </button>

                <button type="submit" class="tombol-simpan">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@push('styles')
    @vite('resources/css/tugas.css')
@endpush

@push('scripts')
    @vite('resources/js/tugas.js')
@endpush
