<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    // Menampilkan daftar tugas
    public function index(Request $request)
    {
        $query = Auth::user()->tugas();

        // Filter tugas
        $filter = $request->query('filter', 'semua');

        if ($filter === 'belum') {
            $query->where('status', 'belum_selesai');
        } elseif ($filter === 'selesai') {
            $query->where('status', 'selesai');
        }

        // Urutkan berdasarkan deadline
        $urut = $request->query('urut', 'terdekat');

        if ($urut === 'terjauh') {
            $query->orderByRaw('deadline IS NULL')
                  ->orderBy('deadline', 'desc');
        } else {
            $query->orderByRaw('deadline IS NULL')
                  ->orderBy('deadline', 'asc');
        }

        $tugas = $query->get();

        // Ringkasan tugas
        $semuaTugas = Auth::user()->tugas()->get();

        $totalTugas = $semuaTugas->count();

        $belumSelesai = $semuaTugas
            ->where('status', 'belum_selesai')
            ->count();

        $selesai = $semuaTugas
            ->where('status', 'selesai')
            ->count();

        $terlambat = $semuaTugas
            ->where('status', 'belum_selesai')
            ->filter(function ($item) {
                return $item->deadline
                    && $item->deadline->lt(today());
            })
            ->count();

        return view('user.tugas', compact(
            'tugas',
            'totalTugas',
            'belumSelesai',
            'selesai',
            'terlambat'
        ));
    }

    // Halaman tambah tidak digunakan karena memakai modal
    public function create()
    {
        return redirect()->route('tugas.index');
    }

    // Menyimpan tugas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'deadline' => 'nullable|date',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'status' => 'required|in:belum_selesai,selesai',
        ]);

        Auth::user()->tugas()->create($validated);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    // Menampilkan detail tugas
    public function show(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        return view('user.detail_tugas', compact('tugas'));
    }

    // Halaman edit tidak digunakan karena memakai modal
    public function edit(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        return redirect()->route('tugas.index');
    }

    // Memperbarui tugas atau statusnya
    public function update(Request $request, Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:150',
            'deadline' => 'sometimes|nullable|date',
            'prioritas' => 'sometimes|required|in:rendah,sedang,tinggi',
            'status' => 'sometimes|required|in:belum_selesai,selesai',
        ]);

        $tugas->update($validated);

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    // Menghapus tugas
    public function destroy(Tugas $tugas)
    {
        $this->authorizeTugas($tugas);

        $tugas->delete();

        return redirect()
            ->route('tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    // Memastikan tugas milik pengguna yang login
    private function authorizeTugas(Tugas $tugas): void
    {
        abort_unless(
            $tugas->user_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke tugas ini.'
        );
    }
}
