<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.playlist', compact('playlists'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:150'],
        ], [
            'name.required' => 'Nama playlist wajib diisi.',
        ]);

        Playlist::create([
            'user_id'     => auth()->id(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()->route('user.playlists.index')
            ->with('success', 'Playlist berhasil dibuat.');
    }

    public function destroy(Playlist $playlist)
    {
        abort_if($playlist->user_id !== auth()->id(), 403);

        $playlist->delete();

        return redirect()->route('user.playlists.index')
            ->with('success', 'Playlist berhasil dihapus.');
    }
}