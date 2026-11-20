@extends('layouts.admin')

@section('title', 'Dashboard Admin - CatatIn')
@section('page-heading', 'Dashboard')

@push('styles')
    @vite('resources/css/dashboard-admin.css')
@endpush

@section('content')

    {{-- STATISTIK --}}
    <section class="stat-grid">

        <div class="stat-card">
            <strong>{{ $totalCatatan }}</strong>
            <span>Total catatan</span>
        </div>

        <div class="stat-card">
            <strong>{{ $totalPengguna }}</strong>
            <span>Total pengguna</span>
        </div>

        <div class="stat-card">
            <strong>{{ $totalUpvote }}</strong>
            <span>Total upvote</span>
        </div>

    </section>


    {{-- KELOLA PENGGUNA --}}
    <section class="content-section">

        <div class="section-heading">
            <h2>Kelola pengguna</h2>
        </div>

        <div class="table-card">
            <div class="table-responsive">

                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($pengguna as $user)
                            <tr>
                                <td>{{ $user['nama'] }}</td>
                                <td>{{ $user['nim'] }}</td>
                                <td>{{ $user['jurusan'] }}</td>

                                <td>
                                    <span class="status-user
                                        {{ $user['status'] === 'Aktif'
                                            ? 'status-active'
                                            : 'status-blocked' }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-wrap">

                                        <button
                                            class="action-button dropdown-trigger"
                                            type="button">
                                            Aksi <span>▼</span>
                                        </button>

                                        <div class="action-menu">

                                            <button
                                                class="menu-item view-user"
                                                type="button"
                                                data-name="{{ $user['nama'] }}"
                                                data-nim="{{ $user['nim'] }}"
                                                data-jurusan="{{ $user['jurusan'] }}"
                                                data-status="{{ $user['status'] }}">
                                                Lihat
                                            </button>

                                        </div>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="empty-table">
                                    Belum ada data pengguna.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>

    </section>


    {{-- RINGKASAN LAPORAN --}}
    <section class="report-card">

        <div>
            <h2>Catatan dilaporkan</h2>

            <p>
                Terdapat <strong>{{ $laporan->count() }}</strong>
                catatan yang perlu diperiksa.
            </p>
        </div>

        <button
            class="primary-button"
            id="report-button"
            type="button">
            Periksa laporan
        </button>

    </section>


    {{-- DAFTAR LAPORAN --}}
    <section class="report-list" id="report-list">

        <div class="section-heading">
            <h2>Daftar laporan</h2>
        </div>

        @if ($laporan->isNotEmpty())

            @foreach ($laporan as $note)
                <div class="reported-item">

                    <div class="reported-info">
                        <h3>{{ $note['judul'] }}</h3>
                        <p>Penulis: {{ $note['penulis'] }}</p>
                        <p>Alasan: {{ $note['alasan'] }}</p>
                    </div>

                </div>
            @endforeach

        @else

            <div class="empty-state">
                Tidak ada catatan yang dilaporkan.
            </div>

        @endif

    </section>


    {{-- MODAL DETAIL PENGGUNA --}}
    <div class="modal-overlay" id="user-modal">

        <div class="modal">

            <button
                class="modal-close"
                type="button"
                data-close="user-modal">
                &times;
            </button>

            <h2>Detail pengguna</h2>

            <div class="detail-list">

                <div>
                    <span>Nama</span>
                    <strong id="modal-user-name"></strong>
                </div>

                <div>
                    <span>NIM</span>
                    <strong id="modal-user-nim"></strong>
                </div>

                <div>
                    <span>Jurusan</span>
                    <strong id="modal-user-jurusan"></strong>
                </div>

                <div>
                    <span>Status</span>
                    <strong id="modal-user-status"></strong>
                </div>

            </div>

        </div>

    </div>


    {{-- MODAL DETAIL CATATAN --}}
    <div class="modal-overlay" id="note-modal">

        <div class="modal modal-large">

            <button
                class="modal-close"
                type="button"
                data-close="note-modal">
                &times;
            </button>

            <h2 id="modal-note-title"></h2>

            <p
                class="modal-author"
                id="modal-note-author">
            </p>

            <div
                class="note-content"
                id="modal-note-content">
            </div>

            <div class="report-box">
                <strong>Alasan laporan</strong>
                <p id="modal-note-reason"></p>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
    @vite('resources/js/dashboard-admin.js')
@endpush
