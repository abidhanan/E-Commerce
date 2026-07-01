<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class DuitkuService
{
    public function createInvoice(Order $order, ?string $paymentMethod = null): array
    {
        $order->loadMissing(['user', 'address', 'items.product', 'items.productVariant']);

        $merchantCode = $this->merchantCode();
        $apiKey = $this->apiKey();
        $amount = $this->amount($order);
        $merchantOrderId = $order->order_code;
        $method = $paymentMethod ?: (string) config('duitku.payment_method');

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => $method,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $this->productDetails($order),
            'additionalParam' => (string) $order->id,
            'merchantUserInfo' => (string) $order->user_id,
            'customerVaName' => Str::limit($order->user->name ?? 'Customer', 30, ''),
            'email' => $order->user->email ?? null,
            'phoneNumber' => $order->user->phone ?? $order->address?->phone_number,
            'callbackUrl' => $this->callbackUrl(),
            'returnUrl' => $this->returnUrl(),
            'signature' => $this->invoiceSignature($merchantCode, $merchantOrderId, $amount, $apiKey),
        ];

        try {
            $response = Http::asJson()
                ->acceptJson()
                ->timeout((int) config('duitku.timeout', 15))
                ->post($this->baseUrl().'/transaction', $payload)
                ->throw();
        } catch (ConnectionException $exception) {
            Log::error('Duitku invoice request timeout/connection failed.', [
                'order_code' => $order->order_code,
                'message' => $exception->getMessage(),
            ]);

            throw new RuntimeException('Koneksi ke Duitku timeout. Silakan coba lagi.', previous: $exception);
        } catch (RequestException $exception) {
            Log::error('Duitku invoice request failed.', [
                'order_code' => $order->order_code,
                'status' => $exception->response?->status(),
                'body' => $exception->response?->json() ?? $exception->response?->body(),
            ]);

            throw new RuntimeException('Duitku menolak pembuatan invoice.', previous: $exception);
        }

        $data = $response->json();

        if (! is_array($data)) {
            Log::error('Duitku invoice returned invalid JSON.', [
                'order_code' => $order->order_code,
                'body' => $response->body(),
            ]);

            throw new RuntimeException('Response Duitku tidak valid.');
        }

        if (($data['statusCode'] ?? null) !== '00' || blank($data['paymentUrl'] ?? null)) {
            Log::error('Duitku invoice was not successful.', [
                'order_code' => $order->order_code,
                'response' => $data,
            ]);

            throw new RuntimeException($data['statusMessage'] ?? 'Invoice Duitku gagal dibuat.');
        }

        return $data;
    }

    public function verifyCallback(array $payload): bool
    {
        $merchantCode = (string) ($payload['merchantCode'] ?? '');
        $amount = (string) ($payload['amount'] ?? '');
        $merchantOrderId = (string) ($payload['merchantOrderId'] ?? '');
        $signature = (string) ($payload['signature'] ?? '');

        if ($merchantCode === '' || $amount === '' || $merchantOrderId === '' || $signature === '') {
            return false;
        }

        if (! hash_equals($this->merchantCode(), $merchantCode)) {
            return false;
        }

        return hash_equals(
            $this->callbackSignature($merchantCode, $amount, $merchantOrderId, $this->apiKey()),
            $signature
        );
    }

    public function callbackSignature(string $merchantCode, string $amount, string $merchantOrderId, string $apiKey): string
    {
        return md5($merchantCode.$amount.$merchantOrderId.$apiKey);
    }

    public function invoiceSignature(string $merchantCode, string $merchantOrderId, int $amount, string $apiKey): string
    {
        return hash_hmac('sha256', $merchantCode.$merchantOrderId.$amount, $apiKey);
    }

    private function baseUrl(): string
    {
        return rtrim((bool) config('duitku.sandbox')
            ? (string) config('duitku.sandbox_base_url')
            : (string) config('duitku.production_base_url'), '/');
    }

    private function merchantCode(): string
    {
        $merchantCode = (string) config('duitku.merchant_code');

        if ($merchantCode === '') {
            throw new RuntimeException('DUITKU_MERCHANT_CODE belum dikonfigurasi.');
        }

        return $merchantCode;
    }

    private function apiKey(): string
    {
        $apiKey = (string) config('duitku.api_key');

        if ($apiKey === '') {
            throw new RuntimeException('DUITKU_API_KEY belum dikonfigurasi.');
        }

        return $apiKey;
    }

    private function callbackUrl(): string
    {
        return config('duitku.callback_url') ?: route('payment.callback');
    }

    private function returnUrl(): string
    {
        return config('duitku.return_url') ?: route('payment.return');
    }

    private function amount(Order $order): int
    {
        return (int) round((float) $order->gross_amount);
    }

    private function productDetails(Order $order): string
    {
        $details = $order->items
            ->map(fn ($item) => ($item->product->name ?? 'Produk').' x '.$item->qty)
            ->implode(', ');

        return Str::limit($details !== '' ? $details : 'Order '.$order->order_code, 255, '');
    }
}
