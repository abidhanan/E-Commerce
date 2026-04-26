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
}