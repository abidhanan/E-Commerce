<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller
{
    public function showProduct(Product $product)
    {
        return view('User.catalog', compact('product'));
}
}