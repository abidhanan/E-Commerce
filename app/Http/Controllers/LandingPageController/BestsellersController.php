<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use App\Models\BestSellers as BestSeller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BestsellersController extends Controller
{
    private const MAX_PRODUCTS = 15;

    public function index()
    {
        $selected = BestSeller::query()
            ->orderBy('position')
            ->pluck('product_id')
            ->map(fn($id) => (int) $id)
            ->all();

        return view('Admin.bestsellers.index', [
            'selected' => $selected,
            'maxProducts' => self::MAX_PRODUCTS,
        ]);
    }

    public function load(Request $request): JsonResponse
    {
        $query = Product::query()
            ->with('images')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim((string) $request->search) . '%');
        }

        $products = $query->latest()->paginate(12);
        $selected = BestSeller::query()
            ->orderBy('position')
            ->pluck('product_id')
            ->map(fn($id) => (int) $id)
            ->all();

        return response()->json([
            'data' => $products->items(),
            'has_more' => $products->hasMorePages(),
            'selected' => $selected,
            'selected_count' => count($selected),
            'max_products' => self::MAX_PRODUCTS,
        ]);
    }

    public function updateAll(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => ['nullable', 'array', 'max:' . self::MAX_PRODUCTS],
            'product_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where(
                    fn($query) => $query->where('is_active', true)
                ),
            ],
        ], [
            'product_ids.max' => 'Maksimal hanya boleh memilih ' . self::MAX_PRODUCTS . ' produk.',
            'product_ids.*.exists' => 'Ada produk yang tidak valid atau sudah nonaktif.',
        ]);

        $productIds = collect($validated['product_ids'] ?? [])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        BestSeller::query()->delete();

        foreach ($productIds as $index => $id) {
            BestSeller::create([
                'product_id' => $id,
                'position' => $index,
            ]);
        }

        return redirect()
            ->route('admin.bestsellers.index')
            ->with('success', 'Best seller berhasil diperbarui.');
    }
}
