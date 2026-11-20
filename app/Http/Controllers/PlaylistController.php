<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $playlists = Playlist::where('user_id', auth()->id())
            ->withCount(['catatans' => fn ($q) => $this->dapatDilihat($q)])
            ->latest()
            ->get();

        $aktif = $playlists->firstWhere('id', (int) $request->query('playlist'))
            ?? $playlists->first();

        $isiFolder = $aktif
            ? $this->dapatDilihat($aktif->catatans())->orderByPivot('created_at', 'desc')->get()
            : collect();

        return view('user.playlist', compact('playlists', 'aktif', 'isiFolder'));
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

    // Simpan catatan ke folder (dipanggil dari Dashboard)
    public function attach(Request $request)
    {
        $data = $request->validate([
            'playlist_id' => ['required', 'integer', 'exists:playlists,id'],
            'catatan_id'  => ['required', 'integer', 'exists:catatans,id'],
        ], [
            'playlist_id.required' => 'Pilih folder terlebih dahulu.',
            'playlist_id.exists'   => 'Folder tidak ditemukan.',
            'catatan_id.exists'    => 'Catatan tidak ditemukan.',
        ]);

        $playlist = Playlist::findOrFail($data['playlist_id']);
        abort_if($playlist->user_id !== auth()->id(), 403);

        $catatan = Catatan::findOrFail($data['catatan_id']);
        abort_unless(
            $catatan->user_id === auth()->id()
                || ($catatan->status === 'Terbit' && $catatan->visibilitas === 'publik'),
            403
        );

        if ($playlist->catatans()->whereKey($catatan->id)->exists()) {
            return back()->with('error', 'Catatan sudah ada di folder "' . $playlist->name . '".');
        }

        $playlist->catatans()->attach($catatan->id);

        return back()->with('success', 'Catatan disimpan ke folder "' . $playlist->name . '".');
    }

    // Keluarkan catatan dari folder (catatan asli tidak dihapus)
    public function detach(Playlist $playlist, Catatan $catatan)
    {
        abort_if($playlist->user_id !== auth()->id(), 403);

        $playlist->catatans()->detach($catatan->id);

        return redirect()
            ->route('user.playlists.index', ['playlist' => $playlist->id])
            ->with('success', 'Catatan dikeluarkan dari folder.');
    }

    // Catatan yang boleh dilihat pemilik folder: miliknya sendiri atau publik+terbit
    private function dapatDilihat($query)
    {
        return $query->where(function ($w) {
            $w->where('catatans.user_id', auth()->id())
              ->orWhere(fn ($x) => $x->publik());
        });
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