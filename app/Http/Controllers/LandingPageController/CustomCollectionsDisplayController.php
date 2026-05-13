<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use App\Models\Collections;
use App\Models\CustomCollectionsDisplay;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomCollectionsDisplayController extends Controller
{
    private const MAX_PRODUCTS = 15;

    public function index()
    {
        $collections = Collections::query()
            ->withCount([
                'products as active_products_count' => fn($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();

        $selectedCollectionId = $this->selectedCollectionId();
        $selectedProductCount = CustomCollectionsDisplay::query()
            ->where('collection_id', $selectedCollectionId)
            ->whereNotNull('product_id')
            ->count();

        return view('Admin.custom-collections-displays.index', compact(
            'collections',
            'selectedCollectionId',
            'selectedProductCount'
        ));
    }

    public function choose(Collections $collection)
    {
        $collection->loadCount([
            'products as active_products_count' => fn($query) => $query->where('is_active', true),
        ]);

        return view('Admin.custom-collections-displays.choose', [
            'collection' => $collection,
            'selected' => $this->selectedProductIds($collection),
            'maxProducts' => self::MAX_PRODUCTS,
        ]);
    }

    public function load(Request $request, Collections $collection): JsonResponse
    {
        $query = $collection->products()
            ->with('images')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim((string) $request->search) . '%');
        }

        $products = $query->latest()->paginate(12);
        $selected = $this->selectedProductIds($collection);

        return response()->json([
            'data' => $products->items(),
            'has_more' => $products->hasMorePages(),
            'selected' => $selected,
            'selected_count' => count($selected),
            'max_products' => self::MAX_PRODUCTS,
        ]);
    }

    public function updateAll(Request $request, Collections $collection)
    {
        $validated = $request->validate([
            'product_ids' => ['nullable', 'array', 'max:' . self::MAX_PRODUCTS],
            'product_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where(
                    fn($query) => $query
                        ->where('collection_id', $collection->id)
                        ->where('is_active', true)
                ),
            ],
        ], [
            'product_ids.max' => 'Maksimal hanya boleh memilih ' . self::MAX_PRODUCTS . ' produk.',
            'product_ids.*.exists' => 'Ada produk yang tidak sesuai dengan collection yang dipilih.',
        ]);

        $productIds = collect($validated['product_ids'] ?? [])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        CustomCollectionsDisplay::query()->delete();

        if ($productIds->isEmpty()) {
            return redirect()
                ->route('admin.custom-collections-display.choose', $collection)
                ->with('error', 'harus ada 1 produk yang dipilih untuk menampilkan custom collection.');
        } else {
            foreach ($productIds as $index => $id) {
                CustomCollectionsDisplay::create([
                    'collection_id' => $collection->id,
                    'product_id' => $id,
                    'position' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.custom-collections-display.choose', $collection)
            ->with('success', 'Custom collection berhasil diperbarui.');
    }

    private function selectedCollectionId(): ?int
    {
        $collectionId = CustomCollectionsDisplay::query()
            ->whereNotNull('collection_id')
            ->latest('updated_at')
            ->value('collection_id');

        return $collectionId ? (int) $collectionId : null;
    }

    private function selectedProductIds(Collections $collection): array
    {
        return CustomCollectionsDisplay::query()
            ->where('collection_id', $collection->id)
            ->whereNotNull('product_id')
            ->orderBy('position')
            ->pluck('product_id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }
}