<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;

class AdminCatatanController extends Controller
{
    /**
     * Menampilkan catatan aktif atau catatan yang dihapus sementara.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'aktif');

        if (!in_array($filter, ['aktif', 'dihapus'], true)) {
            $filter = 'aktif';
        }

        $query = Catatan::with(['user', 'category']);

        if ($filter === 'dihapus') {
            $query->onlyTrashed();
        }

        if ($request->filled('cari')) {
            $kataKunci = $request->cari;

            $query->where(function ($q) use ($kataKunci) {
                $q->where('judul', 'like', "%{$kataKunci}%")
                    ->orWhere('matkul', 'like', "%{$kataKunci}%")
                    ->orWhere('jurusan', 'like', "%{$kataKunci}%")
                    ->orWhereHas('user', function ($userQuery) use ($kataKunci) {
                        $userQuery->where('name', 'like', "%{$kataKunci}%");
                    });
            });
        }

        $catatan = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jumlahAktif = Catatan::count();
        $jumlahDihapus = Catatan::onlyTrashed()->count();

        return view('admin.kelola-catatan.index', compact(
            'catatan',
            'filter',
            'jumlahAktif',
            'jumlahDihapus'
        ));
    }

    /**
     * Menampilkan detail catatan, termasuk yang dihapus sementara.
     */
    public function show($id)
    {
        $catatan = Catatan::withTrashed()
            ->with(['user', 'category'])
            ->findOrFail($id);

        return view('admin.kelola-catatan.detail', compact('catatan'));
    }

    /**
     * Memulihkan catatan yang dihapus sementara.
     */
    public function restore($id)
    {
        $catatan = Catatan::onlyTrashed()->findOrFail($id);

        $catatan->restore();

        return redirect()
            ->route('admin.kelola-catatan.index', ['filter' => 'dihapus'])
            ->with('sukses', 'Catatan berhasil dipulihkan.');
    }

    /**
     * Menghapus catatan secara permanen.
     */
    public function forceDelete($id)
    {
        $catatan = Catatan::withTrashed()->findOrFail($id);

        $catatan->forceDelete();

        return redirect()
            ->route('admin.kelola-catatan.index', ['filter' => 'dihapus'])
            ->with('sukses', 'Catatan berhasil dihapus secara permanen.');
    }
}
