<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use App\Models\NoteReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteReportController extends Controller
{
    public function store(Request $request, Catatan $catatan)
    {
        // 1. Cegah mahasiswa melaporkan catatannya sendiri
        if ($catatan->user_id === Auth::id()) {
            return response()->json([
                'message' => 'Kamu tidak dapat melaporkan catatan milikmu sendiri.'
            ], 422);
        }

        // 2. Validasi input sesuai form modal & tabel note_reports
        $validated = $request->validate([
            'judul'     => ['required', 'string', 'max:100'],
            'jenis'     => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ], [
            'judul.required' => 'Judul laporan wajib diisi.',
            'jenis.required' => 'Jenis laporan wajib dipilih.',
        ]);

        // 3. Cegah 1 mahasiswa melaporkan catatan yang sama dua kali
        $alreadyReported = NoteReport::where('catatan_id', $catatan->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'message' => 'Kamu sudah pernah melaporkan catatan ini sebelumnya.'
            ], 422);
        }

        // 4. Simpan ke tabel note_reports
        NoteReport::create([
            'catatan_id' => $catatan->id,
            'user_id'    => Auth::id(),
            'judul'      => $validated['judul'],
            'jenis'      => $validated['jenis'],
            'deskripsi'  => $validated['deskripsi'] ?? null,
            'status'     => 'menunggu',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim dan akan segera ditinjau oleh admin.'
        ]);
    }
}