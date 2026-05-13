<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Collections;
use App\Models\Product;

class CollectionsController extends Controller
{
    public function show(string $slug)
    {
        $collection = Collections::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::query()
            ->where('collection_id', $collection->id)
            ->where('is_active', true)
            ->with(['images', 'variants'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('Users.collections.show', compact('collection', 'products'));
    }
}
