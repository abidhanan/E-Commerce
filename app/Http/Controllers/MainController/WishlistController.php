<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;         // KUNCI MUTLAK REQUEST YANG HILANG
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check(), 401);

        // Relasi yang benar sesuai dengan User.php milikmu adalah 'wishlists'
        $wishlists = auth()->user()
            ->wishlists() 
            ->with(['product.variants', 'product.images', 'product.category', 'product.collection'])
            ->latest()
            ->get();

        return view('Users.wishlist.index', compact('wishlists'));
    }

    public function status(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['product_ids' => [], 'count' => 0], 401);
        }

        return response()->json([
            // Pastikan ini mengambil 'product_id' dari relasi 'wishlists'
            'product_ids' => $user->wishlists()->pluck('product_id')->map(fn($id) => (int) $id)->values(),
            'count' => $user->wishlists()->count(),
        ]);
    }

    // JANGAN LUPA TAMBAHKAN 'Request $request' DI DALAM PARAMETER
    public function toggle(Request $request, Product $product)
    {
        $user = Auth::user();

        $wishlist = DB::table('wishlists')
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            DB::table('wishlists')->where('id', $wishlist->id)->delete();
            $status = 'removed';
            $message = 'Produk dihapus dari Wishlist.';
        } else {
            DB::table('wishlists')->insert([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $status = 'added';
            $message = 'Produk ditambahkan ke Wishlist.';
        }

        // KUNCI MUTLAK AJAX: Deteksi jika ini adalah permintaan dari JavaScript
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $message
            ]);
        }

        // Fallback jika diklik secara manual tanpa JavaScript
        return back()->with('success', $message);
    }

    public function destroy(Product $product): JsonResponse
    {
        $user = Auth::user();

        $user->wishlists()
            ->where('product_id', $product->id)
            ->delete();

        return response()->json([
            'wishlisted' => false,
            'count' => $user->wishlists()->count(),
            'message' => 'Produk dihapus dari wishlist.',
        ]);
    }
}
