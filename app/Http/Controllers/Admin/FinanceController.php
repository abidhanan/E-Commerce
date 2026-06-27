<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderLifecycleService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    private const PAID_STATUSES = ['paid', 'processing', 'shipped', 'completed'];
    private const RECEIVABLE_STATUSES = ['quoted', 'pending', 'challenge'];
    private const ATTENTION_STATUSES = ['waiting_admin', 'quoted', 'pending', 'challenge'];

    private const STATUS_LABELS = [
        'waiting_admin' => 'Menunggu Quote',
        'quoted' => 'Menunggu Pembayaran',
        'pending' => 'Pending Duitku',
        'challenge' => 'Challenge',
        'paid' => 'Dibayar',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'failed' => 'Gagal',
        'refunded' => 'Refund',
    ];

    public function index(Request $request, OrderLifecycleService $lifecycleService): View
    {
        $lifecycleService->completeEstimatedShipments();

        [$from, $to] = $this->dateRange($request, defaultToCurrentMonth: true);

        $periodOrders = Order::query()
            ->whereBetween('created_at', [$from, $to])
            ->get(['id', 'status', 'gross_amount', 'created_at']);

        $recentOrders = Order::query()
            ->with(['user', 'items'])
            ->latest()
            ->limit(8)
            ->get();

        return view('Admin.finance.index', [
            'statusLabels' => self::STATUS_LABELS,
            'from' => $from,
            'to' => $to,
            'summary' => [
                'revenue_total' => Order::query()->whereIn('status', self::PAID_STATUSES)->sum('gross_amount'),
                'revenue_period' => $periodOrders
                    ->whereIn('status', self::PAID_STATUSES)
                    ->sum('gross_amount'),
                'receivables_total' => Order::query()->whereIn('status', self::RECEIVABLE_STATUSES)->sum('gross_amount'),
                'needs_quote_count' => Order::query()->where('status', 'waiting_admin')->count(),
                'orders_period_count' => $periodOrders->count(),
                'paid_period_count' => $periodOrders->whereIn('status', self::PAID_STATUSES)->count(),
            ],
            'statusSummary' => $this->statusSummary($periodOrders),
            'monthlyRevenue' => $this->monthlyRevenue(),
            'topProducts' => $this->topProducts($from, $to),
            'attentionOrders' => Order::query()
                ->with(['user', 'items'])
                ->whereIn('status', self::ATTENTION_STATUSES)
                ->latest()
                ->limit(6)
                ->get(),
            'recentOrders' => $recentOrders,
        ]);
    }

    public function orders(Request $request, OrderLifecycleService $lifecycleService): View
    {
        $lifecycleService->completeEstimatedShipments();

        [$from, $to] = $this->dateRange($request);

        $orders = $this->ordersQuery($request, $from, $to)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('Admin.finance.orders', [
            'orders' => $orders,
            'statusLabels' => self::STATUS_LABELS,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->dateRange($request);
        $filename = 'finance-orders-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($request, $from, $to) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'order_code',
                'tanggal',
                'customer',
                'email',
                'status',
                'qty_item',
                'subtotal',
                'ongkir',
                'total',
                'payment_gateway',
                'payment_reference',
                'payment_method',
                'payment_status',
                'payment_url',
            ]);

            $this->ordersQuery($request, $from, $to)
                ->orderBy('id')
                ->chunkById(200, function ($orders) use ($handle) {
                    foreach ($orders as $order) {
                        fputcsv($handle, [
                            $order->order_code,
                            $order->created_at?->format('Y-m-d H:i:s'),
                            $order->user->name ?? '-',
                            $order->user->email ?? '-',
                            self::STATUS_LABELS[$order->status] ?? $order->status,
                            $order->items->sum('qty'),
                            (float) $order->subtotal,
                            (float) $order->shipping_cost,
                            (float) $order->gross_amount,
                            $order->payment_gateway,
                            $order->payment_reference,
                            $order->payment_method,
                            $order->payment_status,
                            $order->payment_url,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function ordersQuery(Request $request, ?Carbon $from, ?Carbon $to): Builder
    {
        return Order::query()
            ->with(['user', 'items'])
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function (Builder $query) use ($search) {
                    $query->where('order_code', 'like', "%{$search}%")
                        ->orWhereHas('user', function (Builder $query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($from, fn (Builder $query) => $query->where('created_at', '>=', $from))
            ->when($to, fn (Builder $query) => $query->where('created_at', '<=', $to));
    }

    private function dateRange(Request $request, bool $defaultToCurrentMonth = false): array
    {
        $from = $request->filled('date_from')
            ? Carbon::parse($request->date('date_from'))->startOfDay()
            : ($defaultToCurrentMonth ? now()->startOfMonth() : null);

        $to = $request->filled('date_to')
            ? Carbon::parse($request->date('date_to'))->endOfDay()
            : ($defaultToCurrentMonth ? now()->endOfDay() : null);

        if ($from && $to && $from->gt($to)) {
            return [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    private function statusSummary($orders)
    {
        $grouped = $orders->groupBy('status');

        return collect(self::STATUS_LABELS)
            ->map(fn (string $label, string $status) => [
                'status' => $status,
                'label' => $label,
                'count' => $grouped->get($status, collect())->count(),
                'amount' => $grouped->get($status, collect())->sum('gross_amount'),
            ])
            ->filter(fn (array $row) => $row['count'] > 0)
            ->values();
    }

    private function monthlyRevenue()
    {
        $start = now()->startOfMonth()->subMonths(5);
        $orders = Order::query()
            ->whereIn('status', self::PAID_STATUSES)
            ->where('created_at', '>=', $start)
            ->get(['gross_amount', 'created_at']);

        return collect(range(5, 0))
            ->map(function (int $monthsAgo) use ($orders) {
                $month = now()->startOfMonth()->subMonths($monthsAgo);
                $key = $month->format('Y-m');

                return [
                    'label' => $month->format('M Y'),
                    'amount' => $orders
                        ->filter(fn (Order $order) => $order->created_at?->format('Y-m') === $key)
                        ->sum('gross_amount'),
                ];
            });
    }

    private function topProducts(Carbon $from, Carbon $to)
    {
        return OrderItem::query()
            ->with('product')
            ->whereHas('order', function (Builder $query) use ($from, $to) {
                $query->whereIn('status', self::PAID_STATUSES)
                    ->whereBetween('created_at', [$from, $to]);
            })
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
    }
}
