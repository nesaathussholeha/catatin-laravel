@extends('layouts.admin')

@section('title', 'Detail Catatan - CatatIn')

@push('styles')
    @vite('resources/css/kelola-catatan.css')
@endpush

@section('content')
<div class="catatan-admin-page">

    <div class="catatan-page-header">
        <div>
            <h1>Detail Catatan</h1>
            <p>Informasi lengkap catatan yang dibagikan mahasiswa.</p>
        </div>

        <a
            href="{{ route('admin.kelola-catatan.index', ['filter' => $catatan->trashed() ? 'dihapus' : 'aktif']) }}"
            class="catatan-action-button"
        >
            Kembali
        </a>
    </div>

    <div class="catatan-card catatan-detail-card">

        <div class="catatan-detail">
            <h2>{{ $catatan->judul }}</h2>

            <p>
                <strong>Penulis:</strong>
                {{ $catatan->anonim ? 'Anonim' : ($catatan->user->name ?? 'Pengguna tidak ditemukan') }}
            </p>

            <p>
                <strong>Email Penulis:</strong>
                {{ $catatan->anonim ? 'Disembunyikan' : ($catatan->user->email ?? '-') }}
            </p>

            <p><strong>Mata Kuliah:</strong> {{ $catatan->matkul }}</p>

            <p><strong>Jurusan:</strong> {{ $catatan->jurusan }}</p>

            <p><strong>Kategori:</strong> {{ $catatan->category->name ?? '-' }}</p>

            <p><strong>Deskripsi:</strong></p>
            <div>{{ $catatan->deskripsi ?: '-' }}</div>

            <p><strong>Isi Catatan:</strong></p>

                <div class="catatan-full-content">
                    @if ($catatan->isi_html)
                        {!! $catatan->isi_html !!}
                    @else
                        <p>Isi catatan belum tersedia.</p>
                    @endif
                </div>

            <p><strong>Link Catatan:</strong></p>

            @if ($catatan->tautan)
                <a href="{{ $catatan->tautan }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    Buka tautan catatan
                </a>
            @else
                <span>-</span>
            @endif

            <p><strong>Visibilitas:</strong> {{ ucfirst($catatan->visibilitas) }}</p>

            <p><strong>Status:</strong> {{ $catatan->status }}</p>

            <p><strong>Upvote:</strong> {{ $catatan->upvote }}</p>

            <p>
                <strong>Dibuat:</strong>
                {{ $catatan->created_at?->format('d-m-Y H:i') ?? '-' }}
            </p>

            @if ($catatan->trashed())
                <p>
                    <strong>Dihapus sementara:</strong>
                    {{ $catatan->deleted_at?->format('d-m-Y H:i') ?? '-' }}
                </p>
            @endif
        </div>

        @if ($catatan->trashed())
            <div class="catatan-actions">

                <form
                    action="{{ route('admin.kelola-catatan.restore', $catatan->id) }}"
                    method="POST"
                    onsubmit="return confirm('Pulihkan catatan ini?')"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="catatan-action-button">
                        Pulihkan Catatan
                    </button>
                </form>

                <form
                    action="{{ route('admin.kelola-catatan.force-delete', $catatan->id) }}"
                    method="POST"
                    onsubmit="return confirm('Hapus catatan ini secara permanen?')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="catatan-action-button delete-button">
                        Hapus Permanen
                    </button>
                </form>

            </div>
        @endif

    </div>
</div>
@endsection
