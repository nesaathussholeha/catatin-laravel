<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Catatan;

class AuthorProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

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

        $jumlahUnduhan = 0;

        $jumlahMatkul = $catatan
            ->pluck('matkul')
            ->filter()
            ->unique()
            ->count();

        $statistik = [
            'jumlah_catatan' => $jumlahCatatan,
            'total_upvote' => $jumlahUpvote,
            'jumlah_unduhan' => $jumlahUnduhan,
            'jumlah_matkul' => $jumlahMatkul,
        ];

        $kategoriList = $catatan
            ->map(fn($item) => $item->category?->name)
            ->filter()
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
            'jumlahUnduhan',
            'jumlahMatkul',
            'statistik',
            'kategoriList'
        ))->with('penulis', $user);
    }
}
