<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderLifecycleService;
use App\Services\OrderStockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function callback(Request $request, OrderStockService $stockService): JsonResponse
    {
        $orderCode = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        $expectedSignature = hash(
            'sha512',
            $orderCode.$statusCode.$grossAmount.config('midtrans.serverKey')
        );

        if (! hash_equals($expectedSignature, $signatureKey)) {
            abort(403, 'Invalid Midtrans signature.');
        }

        $order = Order::query()
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $stockService->applyStatus($order, $this->mapTransactionStatus(
            (string) $request->input('transaction_status'),
            (string) $request->input('fraud_status'),
            $order->status,
        ));

        return response()->json(['message' => 'ok']);
    }

    public function status(Request $request, string $orderCode, OrderLifecycleService $lifecycleService): View
    {
        $lifecycleService->completeEstimatedShipments();

        $order = Order::query()
            ->with(['address', 'items.product', 'items.productVariant'])
            ->where('order_code', $orderCode)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return view('payments.status', [
            'order' => $order,
            'sourceStatus' => $request->query('transaction_status') ?? $request->query('status'),
        ]);
    }

    private function mapTransactionStatus(string $transactionStatus, string $fraudStatus, string $fallbackStatus): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'challenge' : 'paid',
            'settlement' => 'paid',
            'pending', 'authorize' => 'pending',
            'deny', 'expire', 'cancel', 'failure' => 'failed',
            'refund', 'refunded', 'partial_refund' => 'refunded',
            default => $fallbackStatus,
        };
    }
}
