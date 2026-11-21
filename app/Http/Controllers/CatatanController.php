<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CatatanController extends Controller
{
    private array $jurusan = ['Informatika', 'Sistem Informasi', 'Teknik Komputer', 'Elektro'];

    private function aturan(): array
    {
        return [
            'judul'       => 'required|max:150',
            'matkul'      => 'required|max:100',
            'jurusan'     => 'required|in:' . implode(',', $this->jurusan),
            'category_id' => 'required|exists:categories,id',
            'deskripsi'   => 'nullable|max:255',
            'tipe_isi'    => 'required|in:teks,tautan',
            'tautan'      => 'required_if:tipe_isi,tautan|nullable|url|max:500',
            'isi'         => 'required_if:tipe_isi,teks|nullable|string|max:300000',
            'visibilitas' => 'required|in:pribadi,publik',
            'anonim'      => 'nullable|boolean',
        ];
    }

    private function siapkanData(Request $request): array
    {
        $data = $request->validate($this->aturan());

        if ($data['tipe_isi'] === 'teks') {
            $teks = $this->ekstrakTeks($data['isi'] ?? '');

            if ($teks === null || $teks === '' || mb_strlen($teks) > 50000) {
                throw ValidationException::withMessages([
                    'isi' => 'Isi catatan kosong, tidak valid, atau terlalu panjang (maks 50.000 karakter).',
                ]);
            }

            $data['isi_teks'] = $teks;
            $data['tautan']   = null;
        } else {
            $data['isi']      = null;
            $data['isi_teks'] = null;
        }

        $data['anonim'] = $request->boolean('anonim') && $data['visibilitas'] === 'publik';

        return $data;
    }

    private function ekstrakTeks(string $json): ?string
    {
        $delta = json_decode($json, true);

        if (!is_array($delta) || !isset($delta['ops']) || !is_array($delta['ops'])) {
            return null;
        }

        $teks = '';
        foreach ($delta['ops'] as $op) {
            if (!is_array($op) || !isset($op['insert']) || !is_string($op['insert'])) {
                return null;
            }
            $teks .= $op['insert'];
        }

        return trim($teks);
    }

    /* ---------- Boleh dilihat akun aktif dan nonaktif ---------- */

    public function index()
    {
        $this->tolakJikaDiblokir();

        $catatanSaya = Auth::user()->catatans()->with('category')->latest()->get();

        return view('user.catatan', compact('catatanSaya'));
    }

    public function show(Catatan $catatan)
    {
        $this->tolakJikaDiblokir();

        $adalahPemilik = $catatan->user_id === Auth::id();
        $bolehPublik   = $catatan->status === 'Terbit' && $catatan->visibilitas === 'publik';

        abort_unless($adalahPemilik || $bolehPublik, 404);

        $catatan->load(['category', 'user']);

        $namaKategori = $catatan->category?->name ?? 'Kategori';
        $namaPenulis = $catatan->anonim ? 'Anonim' : ($catatan->user?->name ?? 'Pengguna');
        $inisialPenulis = strtoupper(substr($namaPenulis, 0, 1));
        $tanggal = $catatan->created_at?->translatedFormat('d F Y') ?? '-';

        $urlCatatan = $catatan->tipe_isi === 'tautan'
            ? $catatan->tautan
            : null;

        return view('user.detail-catatan', compact(
            'catatan',
            'namaKategori',
            'namaPenulis',
            'inisialPenulis',
            'tanggal',
            'urlCatatan'
        ));
    }

    /* ---------- Hanya akun aktif ---------- */

    public function create()
    {
        $akun = Auth::user();

        $akunNonaktif = $akun->status !== 'active';

        $pesanNonaktif = match ($akun->status) {
            'inactive' => 'Akun kamu dinonaktifkan karena pelanggaran aturan atau laporan pengguna. Fitur unggah sementara tidak tersedia.',
            'blocked' => 'Akun kamu telah diblokir karena melanggar aturan CatatIn. Kamu tidak dapat mengunggah catatan.',
            default => null,
        };

        return view('user.unggah', [
            'catatan'       => new Catatan(['visibilitas' => 'pribadi']),
            'categories'    => Category::all(),
            'jurusan'       => $this->jurusan,
            'akunNonaktif'  => $akunNonaktif,
            'pesanNonaktif' => $pesanNonaktif,
        ]);
    }

    public function store(Request $request)
    {
        $this->tolakJikaNonaktif();

        Auth::user()->catatans()->create($this->siapkanData($request));

        return redirect()->route('catatan.index')->with('sukses', 'Catatan berhasil disimpan.');
    }

    public function edit(Catatan $catatan)
    {
        $this->tolakJikaNonaktif();
        abort_if($catatan->user_id !== Auth::id(), 404);

        return view('user.unggah', [
            'catatan'    => $catatan,
            'categories' => Category::all(),
            'jurusan'    => $this->jurusan,
        ]);
    }

    public function update(Request $request, Catatan $catatan)
    {
        $this->tolakJikaNonaktif();
        abort_if($catatan->user_id !== Auth::id(), 404);

        $catatan->update($this->siapkanData($request));

        return redirect()->route('catatan.index')->with('sukses', 'Catatan berhasil diperbarui.');
    }

    public function destroy(Catatan $catatan)
    {
        $this->tolakJikaNonaktif();
        abort_if($catatan->user_id !== Auth::id(), 404);

        $catatan->delete();

        return redirect()->route('catatan.index')->with('sukses', 'Catatan berhasil dihapus.');
    }

    // Menampilkan isi catatan berdasarkan jenisnya
    public function getIsiTampilanAttribute(): ?string
    {
        return $this->isi_teks;
    }

    // Mengecek apakah catatan berupa teks
    public function getIsTeksAttribute(): bool
    {
        return $this->tipe_isi === 'teks';
    }
}
