<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $playlists = Playlist::where('user_id', auth()->id())
            ->latest()
            ->get();

        // Folder yang sedang dibuka: diambil dari daftar milik sendiri,
        // jadi ?playlist=ID milik orang lain otomatis diabaikan.
        $aktif = $playlists->firstWhere('id', (int) $request->query('playlist'))
            ?? $playlists->first();

        return view('user.playlist', compact('playlists', 'aktif'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $playlist = Playlist::create([
            'user_id'     => auth()->id(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()
            ->route('user.playlists.index', ['playlist' => $playlist->id])
            ->with('success', 'Folder berhasil dibuat.');
    }

    public function update(Request $request, Playlist $playlist)
    {
        abort_if($playlist->user_id !== auth()->id(), 403);

        $data = $request->validate($this->rules($playlist), $this->messages());

        $playlist->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()
            ->route('user.playlists.index', ['playlist' => $playlist->id])
            ->with('success', 'Folder berhasil diperbarui.');
    }

    public function destroy(Playlist $playlist)
    {
        abort_if($playlist->user_id !== auth()->id(), 403);

        $playlist->delete();

        return redirect()->route('user.playlists.index')
            ->with('success', 'Folder berhasil dihapus.');
    }

    private function rules(?Playlist $playlist = null): array
    {
        return [
            'name' => [
                'required', 'string', 'max:60',
                Rule::unique('playlists', 'name')
                    ->where('user_id', auth()->id())
                    ->ignore($playlist?->id),
            ],
            'description' => ['nullable', 'string', 'max:150'],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required'   => 'Nama folder wajib diisi.',
            'name.max'        => 'Nama folder maksimal 60 karakter.',
            'name.unique'     => 'Kamu sudah punya folder dengan nama ini.',
            'description.max' => 'Deskripsi maksimal 150 karakter.',
        ];
    }
}