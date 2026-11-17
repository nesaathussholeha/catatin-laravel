<?php

namespace App\Http\Controllers;

class UserDashboardController extends Controller
{
    public function index()
    {
        
        $notes = collect([
            ['id' => 1, 'judul' => 'Rangkuman Struktur Data — Pohon & Graf', 'matkul' => 'Struktur Data',      'jurusan' => 'Informatika',      'tipe' => 'Rangkuman', 'penulis' => 'Nazma F.',       'upvote' => 128, 'tanggal' => '2026-09-25'],
            ['id' => 2, 'judul' => 'Kumpulan Soal UAS Basis Data 2025',      'matkul' => 'Basis Data',        'jurusan' => 'Sistem Informasi', 'tipe' => 'Soal Ujian', 'penulis' => 'Sara Wijayanto', 'upvote' => 96,  'tanggal' => '2026-09-22'],
            ['id' => 3, 'judul' => 'Modul Praktikum Rangkaian Digital',      'matkul' => 'Rangkaian Digital', 'jurusan' => 'Elektro',          'tipe' => 'Modul',      'penulis' => 'Nadia Omara',   'upvote' => 54,  'tanggal' => '2026-09-20'],
            ['id' => 4, 'judul' => 'Rangkuman Pemrograman Web — Sesi 1–7',   'matkul' => 'Pemrograman Web',   'jurusan' => 'Informatika',      'tipe' => 'Rangkuman', 'penulis' => 'Afifatul M.',    'upvote' => 41,  'tanggal' => '2026-09-27'],
            ['id' => 5, 'judul' => 'Rangkuman Struktur Data',                'matkul' => 'Struktur Data',     'jurusan' => 'Informatika',      'tipe' => 'Rangkuman', 'penulis' => 'Rizky A.',       'upvote' => 37,  'tanggal' => '2026-09-10'],
            ['id' => 6, 'judul' => 'Modul Praktikum HTML/CSS',               'matkul' => 'Pemrograman Web',   'jurusan' => 'Informatika',      'tipe' => 'Modul',      'penulis' => 'Dimas P.',      'upvote' => 63,  'tanggal' => '2026-09-12'],
        ])->map(fn ($n) => (object) $n)
          ->sortByDesc('tanggal')
          ->values();

        return view('user.dashboard', [
            'notes'          => $notes,
            'daftarJurusan'  => $notes->pluck('jurusan')->unique()->sort()->values(),
            'daftarKategori' => ['Rangkuman', 'Soal Ujian', 'Modul'],
        ]);
    }
}