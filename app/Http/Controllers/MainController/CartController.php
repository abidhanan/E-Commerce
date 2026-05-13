<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->cartPayload($request));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'variant_id' => ['required', 'exists:product_variants,id'],
            'qty' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        $requestedQty = $data['qty'] ?? 1;

        if (blank($variant->size)) {
            return response()->json([
                'message' => 'Size produk ini belum valid.',
            ], 422);
        }

        $cartItem = $request->user()
            ->cartItems()
            ->where('product_variant_id', $data['variant_id'])
            ->first();

        $nextQty = ($cartItem?->qty ?? 0) + $requestedQty;
        if ((int) $variant->stock < $nextQty) {
            return response()->json([
                'message' => "Stok {$variant->product->name} size {$variant->size} tinggal {$variant->stock}.",
            ], 422);
        }

        if ($cartItem) {
            $cartItem->increment('qty', $requestedQty);
        } else {
            $request->user()->cartItems()->create([
                'product_variant_id' => $data['variant_id'],
                'qty' => $requestedQty,
            ]);
        }

        return response()->json([
            ...$this->cartPayload($request),
            'message' => 'Produk ditambahkan ke cart.',
        ]);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 404);

        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cartItem->load(['productVariant.product']);
        $variant = $cartItem->productVariant;

        if ((int) $variant->stock < (int) $data['qty']) {
            return response()->json([
                'message' => "Stok {$variant->product->name} size {$variant->size} tinggal {$variant->stock}.",
            ], 422);
        }

        $cartItem->update(['qty' => $data['qty']]);

        return response()->json([
            ...$this->cartPayload($request),
            'message' => 'Cart diperbarui.',
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 404);

        $cartItem->delete();

        return response()->json([
            ...$this->cartPayload($request),
            'message' => 'Produk dihapus dari cart.',
        ]);
    }

    private function cartPayload(Request $request): array
    {
        $items = $request->user()
            ->cartItems()
            ->with(['productVariant.product.images'])
            ->latest()
            ->get();

        $mappedItems = $items->map(function (CartItem $item) {
            $variant = $item->productVariant;
            $product = $variant->product;
            $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
            $lineTotal = $variant->price * $item->qty;

            return [
                'id' => $item->id,
                'product_name' => $product->name,
                'variant_name' => $variant->size,
                'stock' => (int) $variant->stock,
                'qty' => $item->qty,
                'price' => (int) $variant->price,
                'price_formatted' => 'Rp ' . number_format($variant->price, 0, ',', '.'),
                'line_total' => (int) $lineTotal,
                'line_total_formatted' => 'Rp ' . number_format($lineTotal, 0, ',', '.'),
                'image' => $image ? asset('storage/' . $image->image) : 'https://via.placeholder.com/160',
                'url' => route('product.show', $product->slug),
            ];
        });

        $subtotal = $mappedItems->sum('line_total');

        return [
            'items' => $mappedItems->values(),
            'count' => $items->sum('qty'),
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'checkout_url' => route('checkout.index'),
        ];
    }
}
