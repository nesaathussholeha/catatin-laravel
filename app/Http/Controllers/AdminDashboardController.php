<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $pengguna = User::with('student')
            ->where('role', 'student')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'nim' => $user->student?->student_number ?? '-',
                    'jurusan' => $user->student?->department_id
                        ? 'ID Jurusan ' . $user->student->department_id
                        : 'Belum diatur',
                    'status' => $user->status === 'active'
                        ? 'Aktif'
                        : 'Nonaktif',
                ];
            });

        $totalPengguna = User::where('role', 'student')->count();

        // Belum tersedia tabel catatan dan upvote.
        $totalCatatan = 0;
        $totalUpvote = 0;

        // Belum tersedia tabel laporan.
        $laporan = collect();

        return view('admin.dashboard', compact(
            'pengguna',
            'totalPengguna',
            'totalCatatan',
            'totalUpvote',
            'laporan'
        ));
    }
}
