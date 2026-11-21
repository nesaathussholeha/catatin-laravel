<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function index(Request $request, User $users)
    {
        $query = UserReport::with([
            'reporter',
            'reportedUser',
        ]);

        // Pencarian laporan
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhereHas('reporter', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('reportedUser', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status laporan
        if (in_array($request->status, ['pending', 'completed'])) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // Ringkasan laporan
        $totalReports = UserReport::count();
        $pendingReports = UserReport::where('status', 'pending')->count();
        $completedReports = UserReport::where('status', 'completed')->count();


        $userQuery = User::where('role', 'student');

        // Pencarian pengguna
        if ($request->filled('user_search')) {
            $searchUser = $request->user_search;

            $userQuery->where(function ($q) use ($searchUser) {
                $q->where('name', 'like', "%{$searchUser}%")
                    ->orWhere('username', 'like', "%{$searchUser}%")
                    ->orWhere('email', 'like', "%{$searchUser}%");
            });
        }

        // Filter status akun
        if (in_array($request->user_status, ['active', 'inactive', 'blocked', 'deleted'])) {
            $userQuery->where('status', $request->user_status);
        }

        // Pagination daftar pengguna
        $users = $userQuery->latest()
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();


        return view('admin.user-reports', compact(
            'reports',
            'users',
            'totalReports',
            'pendingReports',
            'completedReports'
        ));
    }

    public function show(UserReport $user_report)
    {
        $user_report->load([
            'reporter',
            'reportedUser',
            'reviewer',
        ]);

        return view('admin.user-reports-detail', [
            'report' => $user_report,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'reported_user_id' => ['required', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'reported_user_id.required' => 'Pengguna yang dilaporkan wajib dipilih.',
            'reported_user_id.exists' => 'Pengguna yang dilaporkan tidak valid.',
            'reason.required' => 'Alasan laporan wajib diisi.',
            'reason.string' => 'Alasan laporan harus berupa teks.',
            'reason.max' => 'Alasan laporan maksimal 255 karakter.',
            'description.string' => 'Keterangan tambahan harus berupa teks.',
        ]);

        // Mencegah pengguna melaporkan akun sendiri
        if ($validated['reported_user_id'] == auth()->id()) {
            return back()->withErrors([
                'reported_user_id' => 'Kamu tidak bisa melaporkan akun sendiri.',
            ])->withInput();
        }

        // Mencegah laporan berulang kepada pengguna yang sama
        $sudahMelaporkan = UserReport::where('reporter_id', auth()->id())
            ->where('reported_user_id', $validated['reported_user_id'])
            ->exists();

        if ($sudahMelaporkan) {
            return back()->withErrors([
                'reported_user_id' => 'Kamu sudah pernah melaporkan pengguna ini. Kamu tidak dapat melaporkannya kembali.',
            ])->withInput();
        }

        UserReport::create([
            'reporter_id' => auth()->id(),
            'reported_user_id' => $validated['reported_user_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Laporan berhasil dikirim.');
    }


    public function update(Request $request, UserReport $user_report)
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:accepted,rejected'],
            'account_status' => [
                'nullable',
                'required_if:decision,accepted',
                'in:inactive,blocked',
            ],
        ]);

        if ($user_report->status === 'completed') {
            return back()->with('error', 'Laporan ini sudah diproses.');
        }

        if ($validated['decision'] === 'accepted') {
            $reportedUser = User::find($user_report->reported_user_id);

            if ($reportedUser && $reportedUser->status === 'deleted') {
                return back()->with(
                    'error',
                    'Akun ini telah dihapus oleh pemiliknya dan statusnya tidak dapat diubah lagi.'
                );
            }
        }

        $user_report->update([
            'status' => 'completed',
            'decision' => $validated['decision'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.user-reports.show', $user_report->id)
            ->with('success', 'Keputusan laporan berhasil disimpan.');
    }


    public function updateUserStatus(Request $request, User $user)
    {
        // Pastikan akun yang diubah adalah mahasiswa
        if ($user->role !== 'student') {
            abort(404);
        }

        // Akun yang sudah dihapus tidak dapat diubah statusnya
        if ($user->status === 'deleted') {
            return back()->with(
                'error',
                'Akun ini telah dihapus oleh pemiliknya dan statusnya tidak dapat diubah lagi.'
            );
        }

        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive,blocked'],
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status pengguna berhasil diperbarui.');
    }
}
