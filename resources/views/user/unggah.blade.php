@extends('layouts.user')

@section('title', $catatan->exists ? 'Edit Catatan' : 'Unggah Catatan')
@section('page-title', $catatan->exists ? 'Edit Catatan' : 'Unggah Catatan')

@push('styles')
    @vite('resources/css/unggah-catatan.css')
@endpush

@push('scripts')
    @vite('resources/js/unggah-catatan.js')
@endpush

@section('content')
    @php
        $edit = $catatan->exists;
        $tipeIsi = old('tipe_isi', $catatan->tipe_isi ?: 'teks');
        $visibilitas = old('visibilitas', $catatan->visibilitas ?: 'pribadi');
        $anonim = (string) (int) old('anonim', $catatan->anonim);

        $opsiVisibilitas = [
            'publik' => ['Publik', 'Dapat dilihat pengguna lain', 'lucide:globe'],
            'pribadi' => ['Pribadi', 'Hanya dapat dilihat kamu', 'lucide:lock'],
        ];
        $opsiIdentitas = [
            '0' => ['Nama profil', 'Menggunakan nama akun', 'lucide:user'],
            '1' => ['Anonim', 'Tanpa nama penulis', 'lucide:user-x'],
        ];
    @endphp

    @if ($akunNonaktif)
        <div class="alert-akun-nonaktif">
            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>

            <div>
                <strong>Fitur unggah dinonaktifkan</strong>
                <p>{{ $pesanNonaktif }}</p>
            </div>
        </div>
    @endif

    <fieldset {{ $akunNonaktif ? 'disabled' : '' }}>
        <form id="form-catatan" action="{{ $edit ? route('catatan.update', $catatan) : route('catatan.store') }}"
            method="POST">
            @csrf
            @if ($edit)
                @method('PUT')
            @endif

            <div class="card-form">
                @if ($errors->any())
                    <div class="alert-galat" role="alert">
                        <ul>
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 01. INFORMASI CATATAN --}}
                <section class="form-section">
                    <h2 class="section-title"><span>01.</span> Informasi Catatan</h2>

                    <div class="form-group">
                        <label for="judul">Judul catatan</label>
                        <input type="text" id="judul" name="judul" maxlength="150"
                            value="{{ old('judul', $catatan->judul) }}" placeholder="Masukkan judul catatan..." required>
                    </div>

                    <div class="form-group">
                        <label for="matkul">Mata kuliah</label>
                        <input type="text" id="matkul" name="matkul" maxlength="100"
                            value="{{ old('matkul', $catatan->matkul) }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <select id="jurusan" name="jurusan" required>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j }}" @selected(old('jurusan', $catatan->jurusan) === $j)>{{ $j }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select id="category_id" name="category_id" required>
                                <option value="" disabled @selected(!old('category_id', $catatan->category_id))>Pilih kategori</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->id }}" @selected((int) old('category_id', $catatan->category_id) === $c->id)>{{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi <span class="label-opsional">(opsional)</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="255"
                            placeholder="Tuliskan ringkasan singkat catatan...">{{ old('deskripsi', $catatan->deskripsi) }}</textarea>
                    </div>
                </section>

                {{-- 02. ISI CATATAN --}}
                <section class="form-section">
                    <h2 class="section-title"><span>02.</span> Isi Catatan</h2>

                    <div class="pill-group dua-kolom mode-toggle">
                        <label class="pill-btn">
                            <input type="radio" name="tipe_isi" value="teks" @checked($tipeIsi === 'teks')>
                            <span><iconify-icon icon="lucide:pen-square"></iconify-icon> Tulis langsung</span>
                        </label>
                        <label class="pill-btn">
                            <input type="radio" name="tipe_isi" value="tautan" @checked($tipeIsi === 'tautan')>
                            <span><iconify-icon icon="lucide:link"></iconify-icon> Gunakan link</span>
                        </label>
                    </div>

                    {{-- Mode: tulis langsung (Quill) --}}
                    <div id="panel-teks" class="panel-isi" @if ($tipeIsi !== 'teks') hidden @endif>
                        <div class="editor-wrap">
                            <div id="editor"></div>
                        </div>
                        <input type="hidden" name="isi" id="isi" value="{{ old('isi', $catatan->isi) }}">
                        <p class="pesan-galat" id="pesan-isi" hidden>Isi catatan belum diisi.</p>
                    </div>

                    {{-- Mode: tautan --}}
                    <div id="panel-tautan" class="panel-isi form-group" @if ($tipeIsi !== 'tautan') hidden @endif>
                        <label for="tautan">Tautan berkas</label>
                        <input type="text" id="tautan" name="tautan" maxlength="500"
                            value="{{ old('tautan', $catatan->tautan) }}" placeholder="https://drive.google.com/...">
                    </div>
                </section>

                {{-- 03. PENGATURAN PUBLIKASI --}}
                <section class="form-section">
                    <h2 class="section-title"><span>03.</span>Pengaturan Publikasi</h2>
                    <p class="section-desc">Atur siapa yang dapat melihat catatan dan bagaimana nama penulis ditampilkan.
                    </p>

                    <div class="form-row">
                        {{-- Visibilitas --}}
                        <div class="form-group">
                            <span class="label-opsi"><iconify-icon icon="lucide:eye"></iconify-icon> Visibilitas</span>
                            <small class="bantuan-teks">Siapa yang bisa melihat catatan?</small>

                            <div class="opsi-list">
                                @foreach ($opsiVisibilitas as $nilai => [$label, $ket, $ikon])
                                    <label class="opsi-kartu">
                                        <input type="radio" name="visibilitas" value="{{ $nilai }}"
                                            @checked($visibilitas === $nilai)>
                                        <span class="opsi-isi">
                                            <iconify-icon class="opsi-ikon" icon="{{ $ikon }}"></iconify-icon>
                                            <span class="opsi-teks">
                                                <strong>{{ $label }}</strong>
                                                <small>{{ $ket }}</small>
                                            </span>
                                            <span class="opsi-titik"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Identitas penulis --}}
                        <div class="form-group">
                            <span class="label-opsi"><iconify-icon icon="lucide:user"></iconify-icon> Identitas
                                Penulis</span>
                            <small class="bantuan-teks">Bagaimana nama ditampilkan? Anonim hanya berlaku untuk catatan
                                publik.</small>

                            <div class="opsi-list" id="grup-identitas">
                                @foreach ($opsiIdentitas as $nilai => [$label, $ket, $ikon])
                                    <label class="opsi-kartu">
                                        <input type="radio" name="anonim" value="{{ $nilai }}"
                                            @checked($anonim === (string) $nilai)>
                                        <span class="opsi-isi">
                                            <iconify-icon class="opsi-ikon" icon="{{ $ikon }}"></iconify-icon>
                                            <span class="opsi-teks">
                                                <strong>{{ $label }}</strong>
                                                <small>{{ $ket }}</small>
                                            </span>
                                            <span class="opsi-titik"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="form-actions">
                <a href="{{ route('catatan.index') }}" class="btn-batal">Batal</a>
                <button type="submit" class="btn-primary">{{ $edit ? 'Perbarui catatan' : 'Simpan catatan' }}</button>
            </div>
        </form>
    </fieldset>

@endsection
