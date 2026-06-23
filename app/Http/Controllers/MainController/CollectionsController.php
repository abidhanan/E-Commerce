<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Collections; // Catatan: Konvensi Laravel yang benar adalah singular (Collection), tapi saya pertahankan agar tidak merusak file Model yang sudah kamu buat.
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionsController extends Controller
{
    public function show(string $slug)
    {
        // 1. Ambil data koleksi beserta jumlah produk aktif di dalamnya
        $collection = Collections::query()
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Tangkap parameter sortir dari request, default ke 'latest'
        $sort = request('sort', 'latest');

        // 3. Bangun query dasar produk untuk koleksi ini
        $productsQuery = Product::query()
            ->where('collection_id', $collection->id)
            ->where('is_active', true)
            ->with(['images', 'variants']);

        // 4. ENGINE SORTIR DINAMIS: Menghubungkan logika Dropdown UI ke Database
        if ($sort === 'price_asc') {
            // Urutkan berdasarkan harga varian termurah (Low to High)
            $productsQuery->orderBy(
                DB::table('product_variants')
                    ->select('price')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->take(1),
                'asc'
            );
        } elseif ($sort === 'price_desc') {
            // Urutkan berdasarkan harga varian termurah (High to Low)
            $productsQuery->orderBy(
                DB::table('product_variants')
                    ->select('price')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->take(1),
                'desc'
            );
        } else {
            // Default: Produk terbaru
            $productsQuery->latest();
        }

        // 5. Eksekusi paginasi dan pertahankan parameter query di URL
        $products = $productsQuery->paginate(12)->withQueryString();

        return view('Users.collections.show', compact('collection', 'products'));
    }
}