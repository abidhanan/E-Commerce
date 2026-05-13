<?php

namespace App\Services;

use App\Models\Order;
use Throwable;

class OrderLifecycleService
{
    public function __construct(private readonly OrderStockService $stockService) {}

    public function completeEstimatedShipments(): void
    {
        Order::query()
            ->where('status', 'shipped')
            ->whereNotNull('delivery_estimated_at')
            ->where('delivery_estimated_at', '<=', now())
            ->chunkById(50, function ($orders) {
                foreach ($orders as $order) {
                    try {
                        $this->complete($order);
                    } catch (Throwable $exception) {
                        report($exception);
                    }
                }
            });
    }

    public function complete(Order $order): Order
    {
        return $this->stockService->applyStatus($order, 'completed', [
            'completed_at' => $order->completed_at ?? now(),
        ]);
    }
}
