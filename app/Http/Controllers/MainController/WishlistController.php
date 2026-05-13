<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Auth::user()
            ->wishlistProducts()
            ->with(['images', 'variants'])
            ->latest('wishlists.created_at')
            ->get();

        return view('Users.wishlist.index', compact('products'));
    }

    public function status(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'product_ids' => $user->wishlistProducts()
                ->pluck('products.id')
                ->values(),
            'count' => $user->wishlists()->count(),
        ]);
    }

    public function toggle(Product $product): JsonResponse
    {
        $user = Auth::user();
        $wishlist = $user->wishlists()
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return response()->json([
                'wishlisted' => false,
                'count' => $user->wishlists()->count(),
                'message' => 'Produk dihapus dari wishlist.',
            ]);
        }

        $user->wishlists()->create([
            'product_id' => $product->id,
        ]);

        return response()->json([
            'wishlisted' => true,
            'count' => $user->wishlists()->count(),
            'message' => 'Produk ditambahkan ke wishlist.',
        ]);
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
