<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DuitkuService;
use App\Services\OrderLifecycleService;
use App\Services\OrderStockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function callback(Request $request, DuitkuService $duitkuService, OrderStockService $stockService): JsonResponse
    {
        $payload = $request->all();

        if (! $duitkuService->verifyCallback($payload)) {
            Log::warning('Invalid Duitku callback signature.', [
                'payload' => $payload,
                'ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'invalid signature'], 400);
        }

        $order = Order::query()
            ->where('order_code', (string) $request->input('merchantOrderId'))
            ->firstOrFail();

        if ($order->payment_reference && $request->input('reference') && $order->payment_reference !== $request->input('reference')) {
            Log::warning('Duitku callback reference mismatch.', [
                'order_code' => $order->order_code,
                'expected_reference' => $order->payment_reference,
                'callback_reference' => $request->input('reference'),
            ]);

            return response()->json(['message' => 'invalid reference'], 400);
        }

        if ((int) round((float) $order->gross_amount) !== (int) $request->input('amount')) {
            Log::warning('Duitku callback amount mismatch.', [
                'order_code' => $order->order_code,
                'expected_amount' => (int) round((float) $order->gross_amount),
                'callback_amount' => $request->input('amount'),
            ]);

            return response()->json(['message' => 'invalid amount'], 400);
        }

        if ($this->isDuplicateCallback($order, (string) $request->input('resultCode'))) {
            Log::info('Duplicate Duitku callback ignored.', [
                'order_code' => $order->order_code,
                'result_code' => $request->input('resultCode'),
                'reference' => $request->input('reference'),
            ]);

            return response()->json(['message' => 'ok']);
        }

        $status = $this->mapDuitkuResultCode((string) $request->input('resultCode'), $order->status);
        $attributes = [
            'payment_gateway' => 'duitku',
            'payment_reference' => $request->input('reference') ?: $order->payment_reference,
            'payment_method' => $request->input('paymentCode') ?: $order->payment_method,
            'payment_status' => $this->paymentStatusForResultCode((string) $request->input('resultCode')),
            'callback_payload' => $payload,
        ];

        if ($status === 'paid') {
            $attributes['paid_at'] = $order->paid_at ?? now();
        }

        $stockService->applyStatus($order, $status, $attributes);

        return response()->json(['message' => 'ok']);
    }

    public function returnView(Request $request): View
    {
        $resultCode = (string) $request->query('resultCode', $request->query('status', ''));

        return view('payments.return', [
            'merchantOrderId' => (string) $request->query('merchantOrderId', ''),
            'reference' => (string) $request->query('reference', ''),
            'status' => match ($resultCode) {
                '00' => 'success',
                '01' => 'pending',
                '02' => 'failed',
                default => 'pending',
            },
        ]);
    }

    public function retry(Request $request, string $orderCode, DuitkuService $duitkuService): RedirectResponse
    {
        $order = Order::query()
            ->with(['user', 'address', 'items.product', 'items.productVariant'])
            ->where('order_code', $orderCode)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if (! in_array($order->status, ['pending', 'failed'], true)) {
            return redirect()->route('payments.status', $order->order_code);
        }

        try {
            $invoice = $duitkuService->createInvoice($order);

            $order->update([
                'payment_gateway' => 'duitku',
                'payment_reference' => $invoice['reference'] ?? null,
                'payment_method' => $invoice['paymentMethod'] ?? config('duitku.payment_method'),
                'payment_url' => $invoice['paymentUrl'],
                'payment_status' => 'pending',
            ]);

            return redirect()->away($invoice['paymentUrl']);
        } catch (\Throwable $exception) {
            Log::error('Duitku payment retry failed.', [
                'order_code' => $order->order_code,
                'message' => $exception->getMessage(),
            ]);

            $order->update(['payment_status' => 'invoice_failed']);

            return redirect()
                ->route('payments.status', $order->order_code)
                ->with('notify', [
                    'type' => 'error',
                    'title' => 'Pembayaran Gagal Dibuat',
                    'message' => 'Invoice pembayaran belum berhasil dibuat. Silakan coba lagi beberapa saat lagi.',
                ]);
        }
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

    private function mapDuitkuResultCode(string $resultCode, string $fallbackStatus): string
    {
        return match ($resultCode) {
            '00' => 'paid',
            '01' => 'failed',
            default => $fallbackStatus,
        };
    }

    private function paymentStatusForResultCode(string $resultCode): string
    {
        return match ($resultCode) {
            '00' => 'success',
            '01' => 'failed',
            default => 'unknown',
        };
    }

    private function isDuplicateCallback(Order $order, string $resultCode): bool
    {
        return match ($resultCode) {
            '00' => $order->payment_status === 'success' || in_array($order->status, ['paid', 'processing', 'shipped', 'completed'], true),
            '01' => $order->payment_status === 'failed' || in_array($order->status, ['failed', 'cancelled', 'refunded'], true),
            default => false,
        };
    }
}
