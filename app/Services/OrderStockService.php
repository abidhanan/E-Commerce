<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderStockService
{
    private const DEDUCT_STOCK_STATUSES = [
        'quoted',
        'paid',
        'processing',
        'shipped',
        'completed',
    ];

    private const RESTORE_STOCK_STATUSES = [
        'waiting_admin',
        'pending',
        'cancelled',
        'failed',
        'refunded',
    ];

    public function applyStatus(Order $order, string $status, array $attributes = []): Order
    {
        return DB::transaction(function () use ($order, $status, $attributes) {
            $lockedOrder = Order::query()
                ->with(['items.product', 'items.productVariant.product'])
                ->lockForUpdate()
                ->findOrFail($order->id);

            $lockedOrder->fill([
                ...$attributes,
                'status' => $status,
            ]);

            if ($this->shouldDeductStock($status)) {
                $this->deductStockIfNeeded($lockedOrder);
            }

            if ($this->shouldRestoreStock($status)) {
                $this->restoreStockIfNeeded($lockedOrder);
            }

            $lockedOrder->save();

            return $lockedOrder->fresh(['items.product', 'items.productVariant.product']);
        });
    }

    private function shouldDeductStock(string $status): bool
    {
        return in_array($status, self::DEDUCT_STOCK_STATUSES, true);
    }

    private function shouldRestoreStock(string $status): bool
    {
        return in_array($status, self::RESTORE_STOCK_STATUSES, true);
    }

    private function deductStockIfNeeded(Order $order): void
    {
        if ($order->stock_deducted_at) {
            return;
        }

        $itemsByVariant = $order->items
            ->groupBy('product_variant_id')
            ->map(fn ($items) => [
                'qty' => $items->sum('qty'),
                'item' => $items->first(),
            ]);

        $variants = ProductVariant::query()
            ->whereIn('id', $itemsByVariant->keys())
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($itemsByVariant as $variantId => $data) {
            $variant = $variants->get($variantId);
            $item = $data['item'];
            $qty = (int) $data['qty'];

            if (! $variant) {
                throw ValidationException::withMessages([
                    'status' => 'Variant produk pada order ini sudah tidak tersedia.',
                ]);
            }

            if ((int) $variant->stock < $qty) {
                throw ValidationException::withMessages([
                    'status' => sprintf(
                        'Stok %s size %s tidak cukup. Tersedia %d, dibutuhkan %d.',
                        $item->product->name ?? 'produk',
                        $variant->size ?? '-',
                        (int) $variant->stock,
                        $qty,
                    ),
                ]);
            }
        }

        foreach ($itemsByVariant as $variantId => $data) {
            $variant = $variants->get($variantId);
            $variant->decrement('stock', (int) $data['qty']);
        }

        $order->stock_deducted_at = now();
    }

    private function restoreStockIfNeeded(Order $order): void
    {
        if (! $order->stock_deducted_at) {
            return;
        }

        $itemsByVariant = $order->items
            ->groupBy('product_variant_id')
            ->map(fn ($items) => $items->sum('qty'));

        $variants = ProductVariant::query()
            ->whereIn('id', $itemsByVariant->keys())
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($itemsByVariant as $variantId => $qty) {
            $variant = $variants->get($variantId);

            if ($variant) {
                $variant->increment('stock', (int) $qty);
            }
        }

        $order->stock_deducted_at = null;
    }
}
