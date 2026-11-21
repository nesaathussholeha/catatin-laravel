<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanUpvoteController extends Controller
{
    public function toggle(Request $request, Catatan $catatan)
    {
        $user = $request->user();

        // Akun nonaktif atau diblokir tidak boleh memberi upvote.
        if ($user->isNonaktif() || $user->isDiblokir()) {
            return response()->json([
                'message' => 'Akun kamu tidak dapat memberikan upvote.',
            ], 403);
        }

        // Catatan harus berstatus Terbit dan publik.
        if (
            $catatan->status !== 'Terbit' ||
            $catatan->visibilitas !== 'publik'
        ) {
            abort(404);
        }

        // Pemilik catatan tidak boleh meng-upvote catatannya sendiri.
        if ($catatan->user_id === $user->id) {
            return response()->json([
                'message' => 'Kamu tidak dapat meng-upvote catatan sendiri.',
            ], 403);
        }

        $hasil = DB::transaction(function () use ($catatan, $user) {
            $catatanTerkunci = Catatan::whereKey($catatan->id)
                ->lockForUpdate()
                ->firstOrFail();

            $sudahUpvote = $catatanTerkunci->upvoters()
                ->where('users.id', $user->id)
                ->exists();

            if ($sudahUpvote) {
                $catatanTerkunci->upvoters()->detach($user->id);

                $catatanTerkunci->decrement('upvote');

                $status = false;
            } else {
                $catatanTerkunci->upvoters()->attach($user->id);

                $catatanTerkunci->increment('upvote');

                $status = true;
            }

            return [
                'upvoted' => $status,
                'upvote' => (int) $catatanTerkunci->fresh()->upvote,
            ];
        });

        return response()->json([
            'message' => $hasil['upvoted']
                ? 'Upvote berhasil ditambahkan!'
                : 'Upvote berhasil dibatalkan.',
            'upvoted' => $hasil['upvoted'],
            'upvote' => $hasil['upvote'],
        ]);
    }
}
