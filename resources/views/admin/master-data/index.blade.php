@extends('layouts.admin')

@section('title', 'Master Data')

@section('page-heading', 'Master Data')

@push('styles')
    @vite('resources/css/master-data.css')
@endpush

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')

    {{-- Notifikasi --}}
    @if (session('success') || session('error'))
        <div class="toast-notification {{ session('success') ? 'toast-success' : 'toast-error' }}" id="toastNotification">

            <iconify-icon icon="{{ session('success') ? 'lucide:check-circle' : 'lucide:alert-circle' }}">
            </iconify-icon>

            <span>{{ session('success') ?? session('error') }}</span>

            <button type="button" class="toast-close" id="btnTutupToast">
                &times;
            </button>
        </div>
    @endif

    {{-- Tab Navigasi --}}
    <div class="master-data-tabs">
        <a href="{{ route('admin.master-data.index', ['tab' => 'jurusan']) }}"
            class="{{ $activeTab === 'jurusan' ? 'active' : '' }}">
            Jurusan
        </a>

        <a href="{{ route('admin.master-data.index', ['tab' => 'matakuliah']) }}"
            class="{{ $activeTab === 'matakuliah' ? 'active' : '' }}">
            Mata Kuliah
        </a>

        <a href="{{ route('admin.master-data.index', ['tab' => 'kategori']) }}"
            class="{{ $activeTab === 'kategori' ? 'active' : '' }}">
            Kategori Dokumen
        </a>
    </div>


    {{-- Konten Tab --}}
    <div class="master-data-content">

        {{-- TAB JURUSAN --}}
        @if ($activeTab === 'jurusan')

            <div class="master-data-heading">
                <div>
                    <h2>Data Jurusan</h2>
                    <p>Kelola daftar jurusan yang tersedia di CatatIn.</p>
                </div>
            </div>

            <div class="master-data-toolbar">
                <div class="search-box">
                    <input type="text" id="searchJurusan" placeholder="Cari kode atau nama jurusan...">
                </div>

                <button type="button" id="btnTambahJurusan">
                    + Tambah Jurusan
                </button>
            </div>

            <div class="master-data-table-wrapper">
                <table class="master-data-table" id="tableJurusan">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Jurusan</th>
                            <th>Nama Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $index => $department)
                            <tr class="baris-jurusan">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->name }}</td>
                                <td class="btn-action">
                                    <button type="button" class="btn-edit-jurusan" data-id="{{ $department->id }}"
                                        data-code="{{ $department->code }}" data-name="{{ $department->name }}">
                                        Edit
                                    </button>

                                    <button type="button" class="btn-hapus-jurusan" data-id="{{ $department->id }}"
                                        data-name="{{ $department->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="dataJurusanKosong">
                                <td colspan="4">Belum ada data jurusan.</td>
                            </tr>
                        @endforelse

                        <tr id="hasilJurusanTidakDitemukan" style="display: none;">
                            <td colspan="4">Jurusan tidak ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- TAB MATA KULIAH --}}
        @elseif ($activeTab === 'matakuliah')
            <div class="master-data-heading">
                <div>
                    <h2>Data Mata Kuliah</h2>
                    <p>Kelola daftar mata kuliah dan jurusan yang menggunakannya.</p>
                </div>
            </div>

            <div class="master-data-toolbar">
                <div class="search-box">
                    <input type="text" id="searchMatakuliah" placeholder="Cari kode atau nama mata kuliah...">
                </div>

                <button type="button" id="btnTambahMatakuliah">
                    + Tambah Mata Kuliah
                </button>
            </div>

            <div class="master-data-table-wrapper">
                <table class="master-data-table" id="tableMatakuliah">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>Jurusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @forelse (($courses ?? collect()) as $index => $course)
                            <tr class="baris-matakuliah">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>
                                    @forelse ($course->departments as $department)
                                        <span class="department-badge">
                                            {{ $department->name }}
                                        </span>
                                    @empty
                                        <span>-</span>
                                    @endforelse
                                </td>
                                <td>
                                    <button type="button" class="btn-edit-matakuliah" data-id="{{ $course->id }}"
                                        data-code="{{ $course->code }}" data-name="{{ $course->name }}"
                                        data-departments="{{ $course->departments->pluck('id')->implode(',') }}">
                                        Edit
                                    </button>

                                    <button type="button" class="btn-hapus-matakuliah" data-id="{{ $course->id }}"
                                        data-name="{{ $course->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="dataMatakuliahKosong">
                                <td colspan="5">Belum ada data mata kuliah.</td>
                            </tr>
                        @endforelse

                        <tr id="hasilMatakuliahTidakDitemukan" style="display: none;">
                            <td colspan="5">Mata kuliah tidak ditemukan.</td>
                        </tr>
                    </tbody>

                </table>
            </div>

            {{-- TAB KATEGORI --}}
        @elseif ($activeTab === 'kategori')
            <div class="master-data-heading">
                <div>
                    <h2>Data Kategori Dokumen</h2>
                    <p>Kelola kategori dokumen yang tersedia di CatatIn.</p>
                </div>
            </div>

            <div class="master-data-toolbar">
                <div class="search-box">
                    <input type="text" id="searchKategori" placeholder="Cari nama kategori...">
                </div>

                <button type="button" id="btnTambahKategori">
                    + Tambah Kategori
                </button>
            </div>

            <div class="master-data-table-wrapper">
                <table class="master-data-table" id="tableKategori">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse (($categories ?? collect()) as $index => $category)
                            <tr class="baris-kategori">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button type="button" class="btn-edit-kategori" data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                        Edit
                                    </button>

                                    <button type="button" class="btn-hapus-kategori" data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="dataKategoriKosong">
                                <td colspan="3">Belum ada data kategori.</td>
                            </tr>
                        @endforelse

                        <tr id="hasilKategoriTidakDitemukan" style="display: none;">
                            <td colspan="3">Kategori tidak ditemukan.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        @endif

    </div>

    </div>


    {{-- Modal Jurusan --}}
    <div class="master-data-modal" id="modalJurusan">
        <div class="master-data-modal-content">

            <div class="master-data-modal-header">
                <h2 id="judulModalJurusan">Tambah Jurusan</h2>
                <button type="button" id="btnTutupModalJurusan">&times;</button>
            </div>

            <form id="formJurusan" action="{{ route('admin.departments.store') }}" method="POST">
                @csrf

                <div class="master-data-modal-body">

                    <input type="hidden" name="_method" id="methodJurusan" value="POST">

                    <div class="form-group">
                        <label for="kodeJurusan">
                            Kode Jurusan <span class="required">*</span>
                        </label>

                        <input type="text" id="kodeJurusan" name="code" value="{{ old('code') }}"
                            placeholder="Masukkan kode jurusan" class="@error('code') is-invalid @enderror">

                        @error('code')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="namaJurusan">
                            Nama Jurusan <span class="required">*</span>
                        </label>

                        <input type="text" id="namaJurusan" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama jurusan" class="@error('name') is-invalid @enderror">

                        @error('name')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>
                </div>



                <div class="master-data-modal-actions">
                    <button type="button" id="btnBatalJurusan">
                        Batal
                    </button>

                    <button type="submit" id="btnSimpanJurusan">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- Modal Hapus Jurusan --}}
    <div class="master-data-modal" id="modalHapusJurusan">
        <div class="master-data-modal-content modal-delete-content">

            <div class="delete-icon">
                <iconify-icon icon="lucide:trash-2"></iconify-icon>
            </div>

            <div class="delete-heading">
                <p>Apakah kamu yakin ingin menghapus jurusan ini?</p>
            </div>

            <div class="delete-info">
                <strong id="namaJurusanHapus"></strong>
            </div>

            <p class="delete-warning">
                Data jurusan akan dihapus dan tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="master-data-modal-actions">
                <button type="button" id="btnBatalHapusJurusan">
                    Batal
                </button>

                <form id="formHapusJurusan" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" id="btnKonfirmasiHapusJurusan">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>


    {{-- Modal Tambah/Edit Mata Kuliah --}}
    <div class="master-data-modal" id="modalMatakuliah">
        <div class="master-data-modal-content">

            <div class="master-data-modal-header">
                <h3 id="judulModalMatakuliah">Tambah Mata Kuliah</h3>

                <button type="button" id="btnTutupModalMatakuliah">
                    &times;
                </button>
            </div>

            <form id="formMatakuliah" action="{{ route('admin.courses.store') }}" method="POST">
                @csrf

                <input type="hidden" name="_method" id="methodMatakuliah" value="POST">

                <div class="master-data-modal-body">
                    <div class="form-group">
                        <label for="kodeMatakuliah">
                            Kode Mata Kuliah <span class="required">*</span>
                        </label>

                        <input type="text" id="kodeMatakuliah" name="code" value="{{ old('code') }}"
                            placeholder="Masukkan kode mata kuliah" class="@error('code') is-invalid @enderror">

                        @error('code')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="namaMatakuliah">
                            Nama Mata Kuliah <span class="required">*</span>
                        </label>

                        <input type="text" id="namaMatakuliah" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama mata kuliah" class="@error('name') is-invalid @enderror">

                        @error('name')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Jurusan yang menggunakan mata kuliah <span class="required">*</span></label>

                        <div class="jurusan-multiselect" id="jurusanMultiSelect">
                            <div class="jurusan-selected" id="jurusanSelected">
                                <span class="placeholder">Pilih jurusan...</span>
                            </div>

                            <div class="jurusan-dropdown">
                                <input type="text" id="searchJurusanSelect" placeholder="Cari jurusan..."
                                    autocomplete="off">

                                <div class="jurusan-options" id="jurusanOptions">
                                    @foreach ($departments as $department)
                                        <label class="jurusan-option">
                                            <input type="checkbox" name="departments[]" value="{{ $department->id }}"
                                                data-name="{{ $department->name }}">
                                            <span>{{ $department->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="master-data-modal-actions">
                    <button type="button" id="btnBatalMatakuliah">
                        Batal
                    </button>

                    <button type="submit" id="btnSimpanMatakuliah">
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- Modal Hapus Mata Kuliah --}}
    <div class="master-data-modal" id="modalHapusMatakuliah">
        <div class="master-data-modal-content modal-delete-content">

            <div class="delete-icon">
                <iconify-icon icon="lucide:trash-2"></iconify-icon>
            </div>

            <div class="delete-heading">
                <p>Apakah kamu yakin ingin menghapus mata kuliah ini?</p>
            </div>

            <div class="delete-info">
                <strong id="namaMatakuliahHapus"></strong>
            </div>

            <p class="delete-warning">
                Data mata kuliah akan dihapus dan tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="master-data-modal-actions">
                <button type="button" id="btnBatalHapusMatakuliah">
                    Batal
                </button>

                <form id="formHapusMatakuliah" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" id="btnKonfirmasiHapusMatakuliah">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>


    {{-- Modal Tambah/Edit Kategori --}}
    <div class="master-data-modal" id="modalKategori">
        <div class="master-data-modal-content">

            <div class="master-data-modal-header">
                <h2 id="judulModalKategori">Tambah Kategori</h2>

                <button type="button" id="btnTutupModalKategori">
                    &times;
                </button>
            </div>

            <form id="formKategori" action="{{ route('admin.categories.store') }}" method="POST">

                @csrf

                <input type="hidden" name="_method" id="methodKategori" value="POST">

                <div class="master-data-modal-body">

                    <div class="form-group">
                        <label for="namaKategori">
                            Nama Kategori <span class="required">*</span>
                        </label>

                        <input type="text" id="namaKategori" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama kategori" class="@error('name') is-invalid @enderror">

                        @error('name')
                            <small class="error-message">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="master-data-modal-actions">
                    <button type="button" id="btnBatalKategori">
                        Batal
                    </button>

                    <button type="submit" id="btnSimpanKategori">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>


    {{-- Modal Hapus Kategori --}}
    <div class="master-data-modal" id="modalHapusKategori">
        <div class="master-data-modal-content modal-delete-content">

            <div class="delete-icon">
                <iconify-icon icon="lucide:trash-2"></iconify-icon>
            </div>

            <div class="delete-heading">
                <p>Apakah kamu yakin ingin menghapus kategori ini?</p>
            </div>

            <div class="delete-info">
                <strong id="namaKategoriHapus"></strong>
            </div>

            <p class="delete-warning">
                Data kategori akan dihapus dan tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="master-data-modal-actions">
                <button type="button" id="btnBatalHapusKategori">
                    Batal
                </button>

                <form id="formHapusKategori" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" id="btnKonfirmasiHapusKategori">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>



@endsection
