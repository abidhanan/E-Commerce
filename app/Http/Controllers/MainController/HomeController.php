<?php

namespace App\Http\Controllers\MainController;

use App\Models\Product; 
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Menarik 4 data produk pertama dari Database
        $trendingProducts = Product::take(5)->get();

        return view('User.home', compact('trendingProducts'));
    }

    // Jangan lupa pastikan kamu mengimpor model Product di bagian atas file ini:
    // use App\Models\Product;

    public function showProduct($id)
    {
        // Cari produk berdasarkan ID, jika tidak ada lemparkan error 404
        $product = \App\Models\Product::findOrFail($id);

        // Arahkan ke file view User/product-detail.blade.php
        return view('User.product-detail', compact('product'));
    }
}