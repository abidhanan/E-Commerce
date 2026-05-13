<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderLifecycleService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private const PAID_STATUSES = ['paid', 'processing', 'shipped', 'completed'];

    private const STATUS_LABELS = [
        'waiting_admin' => 'Menunggu Quote',
        'quoted' => 'Menunggu Pembayaran',
        'pending' => 'Pending',
        'challenge' => 'Challenge',
        'paid' => 'Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'failed' => 'Gagal',
        'refunded' => 'Refund',
    ];

    public function index(OrderLifecycleService $lifecycleService)
    {
        $lifecycleService->completeEstimatedShipments();

        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $allUser = User::with('roles')
            ->latest()
            ->limit(8)
            ->get();

        $totalUsers = User::count();
        $totalOrders = Order::count();
        $paidRevenue = Order::query()
            ->whereIn('status', self::PAID_STATUSES)
            ->sum('gross_amount');
        $activeUsers = ActivityLog::query()
            ->where('event', 'login')
            ->whereNotNull('user_id')
            ->where('created_at', '>=', now()->subDays(30))
            ->distinct('user_id')
            ->count('user_id');

        $dashboardStats = [
            'total_users' => $totalUsers,
            'paid_revenue' => $paidRevenue,
            'total_orders' => $totalOrders,
            'active_users' => $activeUsers,
            'active_products' => Product::query()->where('is_active', true)->count(),
            'orders_need_follow_up' => Order::query()
                ->whereIn('status', ['waiting_admin', 'quoted', 'pending', 'challenge'])
                ->count(),
        ];

        $ordersByStatus = Order::query()
            ->get(['status', 'gross_amount'])
            ->groupBy('status')
            ->map(fn ($orders, string $status) => [
                'status' => $status,
                'label' => self::STATUS_LABELS[$status] ?? str_replace('_', ' ', $status),
                'count' => $orders->count(),
                'amount' => $orders->sum('gross_amount'),
            ])
            ->sortByDesc('count')
            ->values();

        $recentOrders = Order::query()
            ->with(['user', 'items'])
            ->latest()
            ->limit(8)
            ->get();

        $topProducts = OrderItem::query()
            ->with('product')
            ->whereHas('order', fn ($query) => $query->whereIn('status', self::PAID_STATUSES))
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $firstItem = $items->first();

                return [
                    'name' => $firstItem->product->name ?? 'Produk terhapus',
                    'qty' => $items->sum('qty'),
                    'amount' => $items->sum(fn (OrderItem $item) => $item->price * $item->qty),
                ];
            })
            ->sortByDesc('amount')
            ->take(5)
            ->values();

        return view('Admin.Dashboard.index', compact(
            'user',
            'role',
            'allUser',
            'totalUsers',
            'dashboardStats',
            'ordersByStatus',
            'recentOrders',
            'topProducts',
        ));
    }
}
