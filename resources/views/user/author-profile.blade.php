@extends('layouts.user')

@section('title', 'Detail Penulis - CatatIn')

@push('styles')
    @vite(['resources/css/author-profile.css', 'resources/js/author-profile.js'])
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

    <div class="profile-penulis">

        {{-- Breadcrumb --}}
        <div class="profile-breadcrumb">
            <a href="{{ url()->previous() }}">
                <iconify-icon icon="lucide:arrow-left"></iconify-icon>
                Kembali
            </a>

            <span>/</span>
            <span class="breadcrumb-current">Detail Penulis</span>
        </div>

        {{-- Informasi Profil --}}
        <section class="profile-card">
            <div class="profile-main">

                <div class="profile-avatar">
                    @if ($foto)
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto {{ $namaPenulis }}">
                    @else
                        <span>{{ $inisial }}</span>
                    @endif
                </div>

                <div class="profile-identity">
                    <h2>{{ $namaPenulis }}</h2>

                    <p class="profile-major">
                        {{ $jurusan }}
                    </p>

                    <p class="profile-joined">
                        <iconify-icon icon="lucide:calendar-days"></iconify-icon>
                        Bergabung sejak
                        {{ $tanggalBergabung ? $tanggalBergabung->translatedFormat('F Y') : '-' }}
                    </p>
                </div>

                {{-- Tombol Laporan --}}
                @if (auth()->id() !== $penulis->id)
                    <button type="button" class="btn-report" onclick="toggleModal(true)">
                        <iconify-icon icon="lucide:flag"></iconify-icon>
                        Laporkan pengguna
                    </button>
                @endif

            </div>

            {{-- Statistik --}}
            <div class="profile-statistics">

                <div class="profile-stat">
                    <div class="stat-icon">
                        <iconify-icon icon="lucide:file-text"></iconify-icon>
                    </div>
                    <div>
                        <h3>{{ $jumlahCatatan }}</h3>
                        <p>Catatan diunggah</p>
                    </div>
                </div>

                <div class="profile-stat">
                    <div class="stat-icon">
                        <iconify-icon icon="lucide:thumbs-up"></iconify-icon>
                    </div>
                    <div>
                        <h3 id="totalUpvote">
                            {{ number_format($jumlahUpvote, 0, ',', '.') }}
                        </h3>
                        <p>Total upvote</p>
                    </div>
                </div>

                <div class="profile-stat">
                    <div class="stat-icon">
                        <iconify-icon icon="lucide:layers"></iconify-icon>
                    </div>

                    <div>
                        <h3>
                            {{ number_format($jumlahKategori, 0, ',', '.') }}
                        </h3>
                        <p>Total kategori</p>
                    </div>
                </div>

                <div class="profile-stat">
                    <div class="stat-icon">
                        <iconify-icon icon="lucide:book-open"></iconify-icon>
                    </div>
                    <div>
                        <h3>{{ $jumlahMatkul }}</h3>
                        <p>Mata kuliah</p>
                    </div>
                </div>

            </div>
        </section>

        {{-- Daftar Catatan --}}
        <section class="uploaded-section">

            <div class="uploaded-heading">
                <div>
                    <div class="heading-title">
                        <h2>Catatan yang diunggah</h2>

                        <span class="total-badge">
                            {{ $jumlahCatatan }} catatan
                        </span>
                    </div>

                    <p>
                        Jelajahi rangkuman, latihan soal, dan catatan kuliah
                        dari {{ $namaPenulis }}.
                    </p>
                </div>
            </div>

            {{-- Pencarian dan Filter --}}
            <div class="filter-toolbar">

                <div class="search-box">
                    <iconify-icon icon="lucide:search"></iconify-icon>

                    <input type="text" id="searchInput" placeholder="Cari judul atau mata kuliah...">
                </div>

                <div class="filter-select">
                    <iconify-icon icon="lucide:list-filter"></iconify-icon>

                    <select id="categorySelect">
                        <option value="all">Semua kategori</option>

                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori }}">
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-select sort-select">
                    <iconify-icon icon="lucide:arrow-down-wide-narrow"></iconify-icon>

                    <select id="sortSelect">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        <option value="upvote">Upvote terbanyak</option>
                    </select>
                </div>

            </div>

            {{-- Kumpulan Kartu Catatan --}}
            <div class="note-grid" id="noteGrid">

                @foreach ($catatan as $item)
                    <article class="note-card" data-title="{{ $item->judul ?? 'Tanpa Judul' }}"
                        data-category="{{ $item->category?->name ?? 'Umum' }}" data-matkul="{{ $item->matkul ?? '' }}"
                        data-deskripsi="{{ $item->deskripsi ?? '' }}" data-date="{{ $item->created_at?->timestamp ?? 0 }}"
                        data-upvote="{{ $item->upvote ?? 0 }}">

                        {{-- Preview Dokumen --}}
                        <div class="note-preview">
                            <div class="document-preview">

                                <span class="document-type">
                                    {{ $item->category?->name ?? 'Umum' }}
                                </span>

                                <div class="document-title">
                                    {{ $item->judul ?? 'Tanpa Judul' }}
                                </div>

                                <div class="document-lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>

                                <div class="document-pages">
                                    Dokumen
                                </div>

                            </div>
                        </div>

                        {{-- Informasi Catatan --}}
                        <div class="note-information">

                            <div class="note-tags">
                                <span class="note-tag category-tag">
                                    {{ $item->category?->name ?? 'Umum' }}
                                </span>

                                @if ($item->matkul)
                                    <span class="note-tag">
                                        {{ $item->matkul }}
                                    </span>
                                @endif
                            </div>

                            <h3>{{ $item->judul ?? 'Tanpa Judul' }}</h3>

                            <p class="note-description">
                                {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <div class="note-metadata">
                                <span>
                                    {{ $item->tautan ? 'Dokumen tersedia' : 'Dokumen belum tersedia' }}
                                </span>
                            </div>

                            <span class="note-date">
                                {{ $item->created_at ? $item->created_at->translatedFormat('d F Y') : 'Tanggal belum tersedia' }}
                            </span>

                        </div>

                        {{-- Aksi Catatan --}}
                        <div class="note-footer">

                            {{-- Jumlah upvote ditampilkan, belum menjadi tombol interaktif --}}
                            <button type="button" class="upvote-button {{ $item->sudah_upvote ? 'aktif' : '' }}"
                                data-url="{{ route('catatan.upvote', $item->id) }}"
                                aria-pressed="{{ $item->sudah_upvote ? 'true' : 'false' }}">
                                <iconify-icon icon="lucide:thumbs-up"></iconify-icon>

                                <span class="upvote-count">{{ $item->upvote ?? 0 }}</span>

                                <span>Upvote</span>
                            </button>

                            <a href="{{ route('catatan.show', $item->id) }}" class="note-detail-link">
                                Lihat catatan
                                <iconify-icon icon="lucide:arrow-right"></iconify-icon>
                            </a>

                        </div>

                    </article>
                @endforeach

            </div>

            {{-- Pesan Jika Kosong --}}
            <div class="empty-message" id="emptyMessage" hidden>
                <iconify-icon icon="lucide:search-x"></iconify-icon>

                <p>
                    {{ $catatan->isEmpty() ? 'Belum ada catatan yang diunggah.' : 'Tidak ada catatan yang cocok.' }}
                </p>
            </div>

            {{-- Footer Daftar Catatan --}}
            <div class="uploaded-footer">
                <span id="displayCount">
                    Menampilkan {{ $jumlahCatatan }} dari {{ $jumlahCatatan }} catatan
                </span>

                <span>Semua unggahan sudah ditampilkan</span>
            </div>

        </section>

        {{-- Modal Laporan Pengguna --}}
        @if (auth()->id() !== $penulis->id)
            <div class="report-modal {{ $errors->has('reason') || $errors->has('description') || $errors->has('reported_user_id') ? 'active' : '' }}"
                id="reportModal">

                <div class="report-modal-content">

                    <button type="button" class="modal-close" onclick="toggleModal(false)">
                        <iconify-icon icon="lucide:x"></iconify-icon>
                    </button>

                    <div class="modal-icon">
                        <iconify-icon icon="lucide:flag"></iconify-icon>
                    </div>

                    <h3>Laporkan Pengguna</h3>

                    <p>
                        Bantu kami menjaga lingkungan CatatIn tetap aman.
                        Sampaikan alasan kamu melaporkan pengguna ini.
                    </p>

                    <form action="{{ route('user-reports.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="reported_user_id" value="{{ $penulis->id }}">

                        {{-- Alasan Laporan --}}
                        <div class="form-group">
                            <label for="reportReason">
                                Alasan Laporan
                                <span class="required-mark" style="color: red">*</span>
                            </label>

                            <input type="text" name="reason" id="reportReason" maxlength="255"
                                value="{{ old('reason') }}" placeholder="Contoh: Konten tidak pantas">

                            @error('reason')
                                <small class="error-message">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Keterangan Tambahan --}}
                        <div class="form-group">
                            <label for="reportDescription">
                                Keterangan Tambahan
                                <span class="optional-label">(Opsional)</span>
                            </label>

                            <textarea name="description" id="reportDescription" rows="4"
                                placeholder="Jelaskan lebih lanjut alasan laporan kamu...">{{ old('description') }}</textarea>

                            @error('description')
                                <small class="error-message">{{ $message }}</small>
                            @enderror
                        </div>

                        @error('reported_user_id')
                            <small class="error-message">{{ $message }}</small>
                        @enderror

                        <div class="modal-buttons">
                            <button type="button" class="btn-cancel" onclick="toggleModal(false)">
                                Batal
                            </button>

                            <button type="submit" class="btn-submit">
                                Kirim Laporan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        @endif

    </div>

@endsection
