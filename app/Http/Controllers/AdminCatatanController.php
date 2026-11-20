<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use App\Models\Category;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCatatanController extends Controller
{
    /**
     * Menampilkan daftar catatan.
     */
    public function index()
    {
        $catatan = Catatan::with(['user', 'category'])
            ->latest()
            ->paginate(10);

        $penulis = User::where('role', 'student')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('name')->get();

        $departments = Department::orderBy('name')->get();

        return view('admin.kelola-catatan.index', compact(
            'catatan',
            'penulis',
            'categories',
            'departments'
        ));
    }

    /**
     * Menyimpan catatan baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'student'),
            ],
            'judul' => 'required|string|max:150',
            'matkul' => 'required|string|max:100',
            'jurusan' => 'required|exists:departments,name',
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'required|string|max:255',
            'tautan' => 'required|url|max:500',
            'visibilitas' => 'required|in:pribadi,publik',
            'anonim' => 'nullable|boolean',
        ]);

        $data['anonim'] = $request->boolean('anonim');

        $catatanBaru = new Catatan($data);
        $catatanBaru->status = 'Draft';
        $catatanBaru->upvote = 0;
        $catatanBaru->save();

        return redirect()
            ->route('admin.kelola-catatan.index')
            ->with('sukses', 'Catatan berhasil ditambahkan.');
    }

    /**
     * Memperbarui catatan.
     */
    public function update(Request $request, Catatan $catatan)
    {
        $data = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'student'),
            ],
            'judul' => 'required|string|max:150',
            'matkul' => 'required|string|max:100',
            'jurusan' => 'required|string|max:50',            
            'category_id' => 'required|exists:categories,id',
            'deskripsi' => 'required|string|max:255',
            'tautan' => 'required|url|max:500',
            'visibilitas' => 'required|in:pribadi,publik',
            'anonim' => 'nullable|boolean',
        ]);

        $data['anonim'] = $request->boolean('anonim');

        $catatan->fill($data);
        $catatan->save();

        return redirect()
            ->route('admin.kelola-catatan.index')
            ->with('sukses', 'Catatan berhasil diperbarui.');
    }

    /**
     * Mengubah status catatan.
     */
    public function updateStatus(Request $request, Catatan $catatan)
    {
        $data = $request->validate([
            'status' => 'required|in:Draft,Terbit,Dilaporkan',
        ]);

        $catatan->status = $data['status'];
        $catatan->save();

        return redirect()
            ->route('admin.kelola-catatan.index')
            ->with('sukses', 'Status catatan berhasil diperbarui.');
    }

    /**
     * Menghapus catatan.
     */
    public function destroy(Catatan $catatan)
    {
        $catatan->delete();

        return redirect()
            ->route('admin.kelola-catatan.index')
            ->with('sukses', 'Catatan berhasil dihapus.');
    }
}
