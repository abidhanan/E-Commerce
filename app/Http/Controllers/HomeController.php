<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Wajib import Model Product

class HomeController extends Controller
{
    public function index()
    {
        // Menarik 4 data produk pertama dari Database
        $trendingProducts = Product::take(5)->get();

        return view('User.home', compact('trendingProducts'));
    }
}