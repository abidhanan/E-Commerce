<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\OrderReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        // KUNCI MUTLAK (Zero-Trust): Cari berdasarkan ID ulasan DAN ID Pengguna yang sedang login.
        // Jika user mencoba mengedit ulasan orang lain, sistem akan memuntahkan Error 404/403.
        $review = OrderReview::where('id', $id)
                             ->where('user_id', Auth::id())
                             ->firstOrFail();

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // KUNCI MUTLAK: Hanya pemilik asli yang bisa menghapus
        $review = OrderReview::where('id', $id)
                             ->where('user_id', Auth::id())
                             ->firstOrFail();

        $review->delete();

        return back()->with('success', 'Ulasan telah dihapus.');
    }
}