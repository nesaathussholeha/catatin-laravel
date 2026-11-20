
@extends('layouts.user')

@section('title', 'Tambah Tugas')
@section('page-title', 'Tambah Tugas')

@section('content')
<div class="form-tugas-container">

    <div class="form-tugas-header">
        <div>
            <h2>Tambah Tugas Baru</h2>
            <p>Isi informasi tugas yang ingin kamu catat.</p>
        </div>

        <a href="{{ route('tugas.index') }}" class="tombol-kembali">
            ← Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="pesan-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tugas.store') }}" method="POST" class="form-tugas">
        @csrf

        <div class="form-group">
            <label for="judul">Judul Tugas <span>*</span></label>
            <input
                type="text"
                id="judul"
                name="judul"
                value="{{ old('judul') }}"
                maxlength="150"
                placeholder="Contoh: Membuat laporan praktikum"
                required
            >
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="4"
                placeholder="Tambahkan keterangan tugas jika diperlukan..."
            >{{ old('deskripsi') }}</textarea>
        </div>

        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input
                type="date"
                id="deadline"
                name="deadline"
                value="{{ old('deadline') }}"
            >
        </div>

        <div class="form-group">
            <label for="prioritas">Prioritas <span>*</span></label>
            <select id="prioritas" name="prioritas" required>
                <option value="rendah" {{ old('prioritas', 'sedang') === 'rendah' ? 'selected' : '' }}>
                    Rendah
                </option>

                <option value="sedang" {{ old('prioritas', 'sedang') === 'sedang' ? 'selected' : '' }}>
                    Sedang
                </option>

                <option value="tinggi" {{ old('prioritas', 'sedang') === 'tinggi' ? 'selected' : '' }}>
                    Tinggi
                </option>
            </select>
        </div>

        <div class="form-tugas-actions">
            <a href="{{ route('tugas.index') }}" class="tombol-batal">
                Batal
            </a>

            <button type="submit" class="tombol-simpan">
                Simpan Tugas
            </button>
        </div>
    </form>

</div>
@endsection

@push('styles')
<style>
    .form-tugas-container {
        max-width: 760px;
        color: #17233f;
    }

    .form-tugas-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .form-tugas-header h2 {
        margin: 0 0 6px;
        font-size: 22px;
    }

    .form-tugas-header p {
        margin: 0;
        color: #718096;
        font-size: 14px;
    }

    .tombol-kembali {
        color: #304b85;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
    }

    .form-tugas {
        display: flex;
        flex-direction: column;
        gap: 19px;
        padding: 25px;
        border: 1px solid #e5eaf2;
        border-radius: 12px;
        background: white;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
    }

    .form-group label span {
        color: #dc3545;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 13px;
        border: 1px solid #dce2ec;
        border-radius: 8px;
        background: white;
        color: #17233f;
        font: inherit;
        font-size: 14px;
    }

    .form-group textarea {
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #304b85;
        box-shadow: 0 0 0 3px rgba(48, 75, 133, .1);
    }

    .form-tugas-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 8px;
    }

    .tombol-batal,
    .tombol-simpan {
        padding: 11px 17px;
        border: 0;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .tombol-batal {
        background: #eef1fb;
        color: #304b85;
    }

    .tombol-simpan {
        background: #10234f;
        color: white;
    }

    .pesan-error {
        margin-bottom: 18px;
        padding: 12px 16px;
        border-radius: 9px;
        background: #fde8e8;
        color: #b42318;
        font-size: 14px;
    }

    .pesan-error ul {
        margin: 0;
        padding-left: 20px;
    }

    @media (max-width: 600px) {
        .form-tugas {
            padding: 18px;
        }

        .form-tugas-actions {
            flex-direction: column-reverse;
        }

        .form-tugas-actions a,
        .form-tugas-actions button {
            text-align: center;
        }
    }
</style>
@endpush
