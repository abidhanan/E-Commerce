<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\Product;

class CategoriesController extends Controller
{
    public function index($slug)
    {
        $category = CategoryProduct::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::query()
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['images', 'variants'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('Users.categories.index', compact('category', 'products'));
    }
}
