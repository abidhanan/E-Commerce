<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class PaymentTesterController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with([
                'variants' => fn ($query) => $query->orderBy('price')->orderBy('id'),
                'images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('id'),
            ])
            ->where('is_active', true)
            ->orderByDesc('id')
            ->get();

        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->take(8)
            ->get();

        return view('Users.dashboard.payment-tester', [
            'products' => $products,
            'orders' => $orders,
        ]);
    }
}
