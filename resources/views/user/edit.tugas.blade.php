
@extends('layouts.user')

@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')

@section('content')
<div class="form-tugas-container">

    <div class="form-tugas-header">
        <div>
            <h2>Edit Tugas</h2>
            <p>Perbarui informasi tugas yang sudah kamu buat.</p>
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

    <form action="{{ route('tugas.update', $tugas) }}" method="POST" class="form-tugas">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="judul">Judul Tugas <span>*</span></label>
            <input
                type="text"
                id="judul"
                name="judul"
                value="{{ old('judul', $tugas->judul) }}"
                maxlength="150"
                required
            >
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                rows="4"
            >{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
        </div>

        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input
                type="date"
                id="deadline"
                name="deadline"
                value="{{ old('deadline', $tugas->deadline?->format('Y-m-d')) }}"
            >
        </div>

        <div class="form-group">
            <label for="prioritas">Prioritas <span>*</span></label>
            <select id="prioritas" name="prioritas" required>
                @foreach (['rendah' => 'Rendah', 'sedang' => 'Sedang', 'tinggi' => 'Tinggi'] as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('prioritas', $tugas->prioritas) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status Tugas <span>*</span></label>
            <select id="status" name="status" required>
                <option value="belum_selesai"
                    {{ old('status', $tugas->status) === 'belum_selesai' ? 'selected' : '' }}>
                    Belum Selesai
                </option>

                <option value="selesai"
                    {{ old('status', $tugas->status) === 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>
            </select>
        </div>

        <div class="form-tugas-actions">
            <a href="{{ route('tugas.index') }}" class="tombol-batal">
                Batal
            </a>

            <button type="submit" class="tombol-simpan">
                Simpan Perubahan
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
</style>
@endpush
