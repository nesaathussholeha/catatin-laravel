@extends('layouts.user')
@section('title', 'Detail Catatan')
@section('page-title', 'Detail Catatan')

@push('styles')
    @vite('resources/css/detail-catatan.css')
@endpush

@push('scripts')
    @vite('resources/js/detail-catatan.js')
@endpush

@section('content')

    <div class="detail-catatan">
        <a href="{{ route('user.dashboard') }}" class="kembali">
            ← Kembali ke Jelajahi Catatan
        </a>

        {{-- Header Catatan --}}
        <section class="kepala-catatan">
            <div class="kepala-info">
                <div class="baris-lencana">
                    <span class="lencana">{{ $catatan->jurusan }}</span>
                    <span class="lencana lencana-netral">{{ $namaKategori }}</span>
                </div>

                <h2>{{ $catatan->judul }}</h2>
                <p class="deskripsi">{{ $catatan->deskripsi }}</p>
                <p class="meta">oleh {{ $namaPenulis }} · {{ $tanggal }}</p>
            </div>

            <div class="aksi-catatan">
                <button type="button" class="tombol" id="btnLaporkan">
                    🚩 Laporkan
                </button>

                @if ($urlCatatan)
                    <a href="{{ $urlCatatan }}" target="_blank" rel="noopener noreferrer" class="tombol tombol-primer">
                        ⬇ Buka / Unduh Catatan
                    </a>
                @endif
            </div>
        </section>

        {{-- Grid Utama --}}
        <div class="grid-detail">
            {{-- Kolom Kiri: Pratinjau --}}
            <section class="kartu">
                <h3 class="judul-kartu">📄 Pratinjau Catatan</h3>

                <div class="kertas">
                    <span class="lencana">CATATAN KULIAH</span>

                    <h4>{{ $catatan->matkul }}</h4>
                    <p class="sub-kertas">{{ $catatan->judul }}</p>

                    <div class="bagian-kertas">
                        <h5>Deskripsi Catatan</h5>
                        <p>{{ $catatan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                    </div>

                    @if ($catatan->is_teks)
                        <div class="bagian-kertas">
                            <h5>Isi Catatan</h5>

                            <div class="isi-catatan-teks">
                                {!! $catatan->isi_html !!}
                            </div>
                        </div>
                    @else
                        <div class="bagian-kertas">
                            <h5>File Catatan</h5>

                            <p>
                                Catatan ini tersedia dalam bentuk tautan.
                                Klik tombol di Buka/Unduh Catatan.
                            </p>
                        </div>
                    @endif

                    <div class="kertas-bawah">
                        <span>{{ $catatan->judul }}</span>
                        <span>Halaman 1 dari 1</span>
                    </div>
                </div>
            </section>

            {{-- Kolom Kanan: Sidebar Info --}}
            <aside class="kolom-samping">
                <div class="kartu">
                    <h3 class="judul-kartu">Penulis</h3>

                    <div class="profil-penulis">
                        @if (!$catatan->anonim && $catatan->user)
                            <a href="{{ route('user.author-profile', ['user' => $catatan->user->id]) }}"
                                class="tautan-profil-penulis">

                                <div class="avatar">{{ $inisialPenulis }}</div>

                                <div>
                                    <div class="nama-penulis">{{ $namaPenulis }}</div>
                                    <div class="peran-penulis">Lihat Profil Penulis →</div>
                                </div>

                            </a>
                        @else
                            <div class="avatar">{{ $inisialPenulis }}</div>

                            <div>
                                <div class="nama-penulis">{{ $namaPenulis }}</div>
                                <div class="peran-penulis">Pengguna CatatIn</div>
                            </div>
                        @endif
                    </div>

                    <p class="meta">
                        Catatan kuliah ini dibagikan agar teman belajar lebih mudah memahami materi.
                    </p>
                </div>

                <div class="kartu">
                    <h3 class="judul-kartu">Info Catatan</h3>
                    <dl class="daftar-info">
                        <div>
                            <dt>Mata Kuliah</dt>
                            <dd>{{ $catatan->matkul }}</dd>
                        </div>
                        <div>
                            <dt>Jurusan</dt>
                            <dd>{{ $catatan->jurusan }}</dd>
                        </div>
                        <div>
                            <dt>Kategori</dt>
                            <dd>{{ $namaKategori }}</dd>
                        </div>
                        <div>
                            <dt>Visibilitas</dt>
                            <dd>{{ ucfirst($catatan->visibilitas ?? 'Publik') }}</dd>
                        </div>
                        <div>
                            <dt>Tanggal</dt>
                            <dd>{{ $tanggal }}</dd>
                        </div>
                    </dl>
                    <div class="terverifikasi">✓ Catatan tersedia di CatatIn</div>
                </div>

                <button type="button" class="tombol-lapor-bawah" id="btnLaporkanBawah">
                    Ada masalah? Laporkan catatan ini
                </button>
            </aside>
        </div>

        {{-- Modal Laporan --}}
        <div class="latar-modal" id="modalLaporan" hidden>
            <div class="kotak-modal" role="dialog" aria-modal="true" aria-labelledby="judulModal">
                <div class="kepala-modal">
                    <div>
                        <h3 id="judulModal">Buat Laporan</h3>
                        <p class="meta">Lengkapi informasi berikut untuk melaporkan catatan ini.</p>
                    </div>
                    <button type="button" class="tutup-modal" id="modalClose" aria-label="Tutup">✕</button>
                </div>

                <form id="formLaporan" action="{{ route('catatan.report.store', $catatan->id) }}" method="POST"
                    class="isi-modal" novalidate>
                    @csrf
                    <input type="hidden" name="catatan_id" value="{{ $catatan->id }}">
                    <input type="hidden" name="jenis" id="inputJenis">

                    <div class="grup-input">
                        <label for="inputJudul">Judul Laporan <span class="wajib">*</span></label>
                        <input type="text" id="inputJudul" name="judul" maxlength="100"
                            placeholder="Contoh: File tidak bisa dibuka">
                        <div class="bawah-input">
                            <span class="teks-error" data-error="judul"></span>
                            <span class="penghitung" id="counterJudul">0/100</span>
                        </div>
                    </div>

                    <div class="grup-input">
                        <label>Jenis Laporan <span class="wajib">*</span></label>
                        <div class="grup-chip" id="chipGroup">
                            <button type="button" class="chip" data-value="File Rusak">File Rusak</button>
                            <button type="button" class="chip" data-value="Salah Mata Kuliah">Salah Mata Kuliah</button>
                            <button type="button" class="chip" data-value="Spam">Spam</button>
                        </div>
                        <span class="teks-error" data-error="jenis"></span>
                    </div>

                    <div class="grup-input">
                        <label for="inputDeskripsi">Deskripsi / Catatan <span class="opsional">(Opsional)</span></label>
                        <textarea id="inputDeskripsi" name="deskripsi" maxlength="500" rows="3"
                            placeholder="Jelaskan secara singkat alasan pelaporan..."></textarea>
                        <div class="bawah-input">
                            <span class="teks-error" data-error="deskripsi"></span>
                            <span class="penghitung" id="counterDeskripsi">0/500</span>
                        </div>
                    </div>

                    <div class="kaki-modal">
                        <button type="button" class="tombol" id="btnBatal">Batal</button>
                        <button type="submit" class="tombol tombol-primer">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
