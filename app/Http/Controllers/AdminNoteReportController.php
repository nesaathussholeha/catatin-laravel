<?php

namespace App\Http\Controllers;

use App\Models\NoteReport;
use Illuminate\Http\Request;

class AdminNoteReportController extends Controller
{
    public function index()
    {
        $laporan = NoteReport::with(['catatan', 'user'])
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'catatan'   => $item->catatan->judul ?? 'Catatan Dihapus',
                    'pelapor'   => $item->user->name ?? 'Pengguna',
                    'alasan'    => $item->jenis . ' — ' . $item->judul,
                    'tanggal'   => $item->created_at ? $item->created_at->format('Y-m-d') : null,
                    'status'    => $item->status,
                    'deskripsi' => $item->deskripsi ?? '-',
                ];
            });

        return view('admin.kelola-laporan.index', compact('laporan'));
    }

    public function updateStatus(Request $request, NoteReport $noteReport)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai'],
        ]);

        $noteReport->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status laporan berhasil diperbarui.',
            'status'  => $noteReport->status,
        ]);
    }

    public function destroy(NoteReport $noteReport)
    {
        $noteReport->delete();

        return response()->json([
            'message' => 'Laporan berhasil dihapus.',
        ]);
    }
}