<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderPaymentLinkNotification;
use App\Services\OrderLifecycleService;
use App\Services\OrderStockService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class OrderController extends Controller
{
    public function index(OrderLifecycleService $lifecycleService)
    {
        $lifecycleService->completeEstimatedShipments();

        $orders = Order::query()
            ->with(['user', 'items'])
            ->latest()
            ->paginate(12);

        return view('Admin.orders.index', compact('orders'));
    }

    public function show(Order $order, OrderLifecycleService $lifecycleService)
    {
       $lifecycleService->completeEstimatedShipments();

      $order->refresh()->load([
            'user',
            'address',
            'items.product',
            'items.productVariant',
            'review',
            'complaints.photos',
        ]);

        return view('Admin.orders.show', compact('order'));
    }

    public function quote(Request $request, Order $order, OrderStockService $stockService)
    {
        $data = $request->validate([
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'gross_amount' => ['required', 'numeric', 'min:0'],
            'payment_url' => ['nullable', 'url', 'max:2048'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $updatedOrder = $stockService->applyStatus($order, 'quoted', [
            'shipping_cost' => $data['shipping_cost'],
            'gross_amount' => $data['gross_amount'],
            'payment_url' => $data['payment_url'] ?? null,
            'admin_note' => $data['admin_note'] ?? null,
            'quoted_at' => now(),
        ]);

        if ($updatedOrder->payment_url && $updatedOrder->user) {
            try {
                $updatedOrder->user->notify(new OrderPaymentLinkNotification($updatedOrder));
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Konfirmasi harga dan link pembayaran berhasil disimpan.');
    }

    public function updateStatus(Request $request, Order $order, OrderStockService $stockService)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                'waiting_admin',
                'quoted',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled',
            ])],
            'delivery_estimated_at' => ['required_if:status,shipped', 'nullable', 'date'],
        ]);

        $attributes = [];

        if ($data['status'] === 'shipped') {
            $attributes['shipped_at'] = $order->shipped_at ?? now();
            $attributes['delivery_estimated_at'] = $data['delivery_estimated_at'];
        }

        if ($data['status'] === 'completed') {
            $attributes['completed_at'] = $order->completed_at ?? now();
        }

        $stockService->applyStatus($order, $data['status'], $attributes);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}