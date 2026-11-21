<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReport;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    public function index(Request $request)
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

        return view('admin.user-reports', compact(
            'reports',
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

        return view('admin.user-report-detail', [
            'report' => $user_report,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reported_user_id' => ['required', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validated['reported_user_id'] == auth()->id()) {
            return back()->withErrors([
                'reported_user_id' => 'Kamu tidak bisa melaporkan akun sendiri.',
            ]);
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
            User::whereKey($user_report->reported_user_id)
                ->update([
                    'status' => $validated['account_status'],
                ]);
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
}
