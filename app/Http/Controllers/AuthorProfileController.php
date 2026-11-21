<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Catatan;

class AuthorProfileController extends Controller
{
    public function show($user)
    {
        // Cari penulis berdasarkan ID
        $user = User::findOrFail($user);

        abort_unless($user->role === 'student', 404);

        $catatan = Catatan::with('category')
            ->where('user_id', $user->id)
            ->where('status', 'Terbit')
            ->where('visibilitas', 'publik')
            ->where('anonim', false)
            ->latest()
            ->get();

        $namaPenulis = $user->name;
        $inisial = strtoupper(substr($namaPenulis, 0, 2));
        $foto = $user->profile_photo;
        $tanggalBergabung = $user->created_at;

        $jurusan = 'Mahasiswa';

        $jumlahCatatan = $catatan->count();
        $jumlahUpvote = $catatan->sum('upvote');
        $jumlahKategori = $catatan
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->count();

        $jumlahMatkul = $catatan
            ->pluck('matkul')
            ->filter()
            ->unique()
            ->count();

        $statistik = [
            'jumlah_catatan' => $jumlahCatatan,
            'total_upvote' => $jumlahUpvote,
            'jumlah_kategori' => $jumlahKategori,
            'jumlah_matkul' => $jumlahMatkul,
        ];

        $kategoriList = $catatan
            ->map(fn($item) => $item->category?->name ?? 'Umum')
            ->unique()
            ->values();

        return view('user.author-profile', compact(
            'user',
            'catatan',
            'namaPenulis',
            'inisial',
            'foto',
            'jurusan',
            'tanggalBergabung',
            'jumlahCatatan',
            'jumlahUpvote',
            'jumlahKategori',
            'jumlahMatkul',
            'statistik',
            'kategoriList'
        ))->with('penulis', $user);
    }
}
