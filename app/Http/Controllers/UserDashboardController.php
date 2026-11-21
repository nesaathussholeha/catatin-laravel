<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Catatan;
use App\Models\Playlist;

class UserDashboardController extends Controller
{
    public function index()
    {
        $notes = Catatan::with(['user', 'category'])
            ->withExists([
                'upvoters as sudah_diupvote' => function ($query) {
                    $query->where('users.id', auth()->id());
                }
            ])
            ->publik()
            ->latest()
            ->get()
            ->map(fn($c) => (object) [
                'id'            => $c->id,
                'judul'         => $c->judul,
                'matkul'        => $c->matkul,
                'jurusan'       => $c->jurusan,
                'tipe'          => $c->category->name ?? '-',
                'penulis'       => $c->anonim ? 'Anonim' : ($c->user->username ?? $c->user->name ?? 'Pengguna'),
                'upvote'        => (int) $c->upvote,
                'sudah_upvote'  => (bool) $c->sudah_diupvote,
                'tanggal'       => $c->created_at->format('Y-m-d'),
                'tautan'        => Str::startsWith($c->tautan, ['http://', 'https://']) ? $c->tautan : '#',
            ]);

        return view('user.dashboard', [
            'notes'          => $notes,
            'playlists'      => Playlist::where('user_id', auth()->id())->orderBy('name')->get(['id', 'name']),
            'daftarJurusan'  => $notes->pluck('jurusan')->unique()->sort()->values(),
            'daftarKategori' => $notes->pluck('tipe')->unique()->sort()->values(),
        ]);
    }
}
