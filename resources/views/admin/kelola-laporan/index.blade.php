@extends('layouts.admin')

@section('title', 'Kelola Laporan - CatatIn')
@section('page-heading', 'Kelola Laporan')

@push('styles')
    @vite('resources/css/kelola-laporan.css')
@endpush

@push('scripts')
    @vite('resources/js/kelola-laporan.js')
@endpush

@section('content')

<div class="report-admin-page">

    {{-- JUDUL HALAMAN --}}
    <div class="report-page-header">
        <h1>Laporan Masuk</h1>
        <p>Kelola dan tinjau laporan catatan dari pengguna.</p>
    </div>

    {{-- TAB FILTER STATUS --}}
    <div class="filter-tabs" id="filter-tabs" role="tablist">
        <button
            type="button"
            class="tab-btn active"
            data-filter="semua"
            role="tab"
            aria-selected="true">
            Semua
        </button>

        <button
            type="button"
            class="tab-btn"
            data-filter="menunggu"
            role="tab"
            aria-selected="false">
            Menunggu
        </button>

        <button
            type="button"
            class="tab-btn"
            data-filter="diproses"
            role="tab"
            aria-selected="false">
            Diproses
        </button>

        <button
            type="button"
            class="tab-btn"
            data-filter="selesai"
            role="tab"
            aria-selected="false">
            Selesai
        </button>
    </div>

    {{-- TABEL LAPORAN --}}
    <section class="table-card">
        <div class="table-scroll">
            <table class="report-table">
                <thead>
                    <tr>
                        <th class="col-catatan">Catatan</th>
                        <th class="col-center">Pelapor</th>
                        <th class="col-center">Alasan</th>
                        <th class="col-center">Status</th>
                        <th class="col-center">Aksi</th>
                    </tr>
                </thead>

                <tbody id="report-body">
                    {{-- Data ditampilkan melalui kelola-laporan.js --}}
                </tbody>
            </table>
        </div>
    </section>

    {{-- DROPDOWN STATUS --}}
    <div class="status-menu" id="status-menu" role="menu" hidden>
        <button
            type="button"
            class="status-option opt-menunggu"
            data-status="menunggu"
            role="menuitem">
            Menunggu
        </button>

        <button
            type="button"
            class="status-option opt-diproses"
            data-status="diproses"
            role="menuitem">
            Diproses
        </button>

        <button
            type="button"
            class="status-option opt-selesai"
            data-status="selesai"
            role="menuitem">
            Selesai
        </button>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="modal-backdrop" id="detail-modal" aria-hidden="true">
        <div
            class="modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="detail-title">

            <div class="modal-header">
                <h2 id="detail-title">Detail Laporan</h2>

                <button
                    type="button"
                    class="modal-close"
                    data-close
                    aria-label="Tutup">
                    <iconify-icon icon="lucide:x" width="18"></iconify-icon>
                </button>
            </div>

            <div class="modal-body" id="detail-body"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-close>
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS --}}
    <div class="modal-backdrop" id="delete-modal" aria-hidden="true">
        <div
            class="modal modal-sm"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="delete-title">

            <div class="modal-header">
                <h2 id="delete-title">Hapus Laporan?</h2>
            </div>

            <div class="modal-body">
                <p class="confirm-text" id="delete-text"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-close>
                    Batal
                </button>

                <button type="button" class="btn btn-danger" id="confirm-delete">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- DATA LAPORAN DARI CONTROLLER --}}
    <script>
        window.LAPORAN_DATA = @json($laporan ?? []);
    </script>

</div>

@endsection