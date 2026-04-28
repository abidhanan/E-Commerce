<?php

namespace App\Http\Controllers\MainController;

use App\Models\Product; 
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $trendingProducts = Product::take(5)->get();

        return view('User.home', compact('trendingProducts'));
    }

    public function showProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        return view('User.product-detail', compact('product'));
    }
}