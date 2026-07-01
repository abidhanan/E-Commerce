<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Http\Requests\PlaceOrderRequest;
use App\Services\MidtransService;
use Throwable;

class CheckoutController extends Controller
{
    public function review(Request $request): View
    {
        [$items, $subtotal] = $this->cartReviewItems($request);

        return view('Users.checkout.review', [
            'items' => $items,
            'subtotal' => $subtotal,
            'addresses' => $this->addresses($request),
            'source' => 'cart',
            'variantId' => null,
        ]);
    }

    public function checkout(Request $request, int $variantId): View
    {
        [$items, $subtotal] = $this->directReviewItems($variantId);

        return view('Users.checkout.review', [
            'items' => $items,
            'subtotal' => $subtotal,
            'addresses' => $this->addresses($request),
            'source' => 'direct',
            'variantId' => $variantId,
        ]);
    }

    // PERBAIKAN 1: Parameter Request diganti dengan PlaceOrderRequest
    public function placeOrder(PlaceOrderRequest $request): RedirectResponse
    {
        // PERBAIKAN 2: Data ditarik dari hasil validasi Form Request, controller bersih!
        $data = $request->validated();

        $address = $request->user()
            ->addresses()
            ->find($data['address_id']);

        if (! $address) {
            throw ValidationException::withMessages([
                'address_id' => 'Alamat pengiriman tidak valid.',
            ]);
        }

        $requestedItems = $this->requestedOrderItems($request, $data);

        if ($requestedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Cart masih kosong.',
            ]);
        }

        $order = DB::transaction(function () use ($request, $data, $address, $requestedItems) {
            $groupedItems = $requestedItems
                ->groupBy('variant_id')
                ->map(fn (Collection $items) => [
                    'variant_id' => $items->first()['variant_id'],
                    'qty' => $items->sum('qty'),
                ])
                ->values();

            $variants = ProductVariant::query()
                ->with('product')
                ->whereIn('id', $groupedItems->pluck('variant_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderItems = $groupedItems->map(function (array $item) use ($variants) {
                $variant = $variants->get($item['variant_id']);
                $qty = (int) $item['qty'];

                if (! $variant || ! $variant->product) {
                    throw ValidationException::withMessages([
                        'stock' => 'Produk pada cart sudah tidak tersedia.',
                    ]);
                }

                if (blank($variant->size)) {
                    throw ValidationException::withMessages([
                        'stock' => "Size {$variant->product->name} belum valid.",
                    ]);
                }

                if ((int) $variant->stock < $qty) {
                    throw ValidationException::withMessages([
                        'stock' => sprintf(
                            'Stok %s size %s tinggal %d, sedangkan pesanan membutuhkan %d.',
                            $variant->product->name,
                            $variant->size,
                            (int) $variant->stock,
                            $qty,
                        ),
                    ]);
                }

                return [
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'price' => $variant->price,
                    'qty' => $qty,
                    'line_total' => $variant->price * $qty,
                ];
            });

            $subtotal = $orderItems->sum('line_total');

            $order = Order::create([
                'order_code' => $this->generateOrderCode(),
                'user_id' => $request->user()->id,
                'address_id' => $address->id,
                'subtotal' => $subtotal,
                'shipping_cost' => null,
                'gross_amount' => $subtotal,
                'status' => 'waiting_admin',
                'customer_note' => $data['customer_note'] ?? null,
            ]);

            $orderItems->each(fn (array $item) => $order->items()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'price' => $item['price'],
                'qty' => $item['qty'],
            ]));

            if ($data['source'] === 'cart') {
                $request->user()->cartItems()->whereIn('id', $data['selected_items'])->delete();
            }

            return $order->load(['user', 'address', 'items.product', 'items.productVariant']);
        });

        try {
            $request->user()->notify(new OrderCreatedNotification($order));
        } catch (Throwable $exception) {
            report($exception);
        }

        return redirect()
            ->route('payments.status', $order->order_code)
            ->with('notify', [
                'type' => 'success',
                'title' => 'Pesanan Dibuat',
                'message' => 'Pesanan berhasil dibuat. Tunggu admin mengonfirmasi ongkir dan link pembayaran.',
            ]);
    }

    public function snap(Request $request, int $variantId, MidtransService $midtransService): JsonResponse
    {
        $variant = ProductVariant::query()->with('product')->findOrFail($variantId);

        if (blank($variant->size) || (int) $variant->stock < 1) {
            return response()->json(['message' => 'Variant produk belum tersedia.'], 422);
        }

        $orderCode = $this->generateOrderCode();

        $itemDetails = [[
            'id' => $variant->sku ?: (string) $variant->id,
            'price' => (int) $variant->price,
            'quantity' => 1,
            'name' => \Illuminate\Support\Str::limit($variant->product->name.' - '.$variant->size, 50, ''),
        ]];

        $customerDetails = [
            'first_name' => $request->user()->name,
            'email' => $request->user()->email,
            'phone' => $request->user()->phone,
        ];

        $snapToken = $midtransService->createSnapToken(
            $orderCode, 
            (int) $variant->price, 
            $itemDetails, 
            $customerDetails
        );

        $order = DB::transaction(function () use ($request, $variant, $orderCode, $snapToken) {
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $request->user()->id,
                'subtotal' => $variant->price,
                'gross_amount' => $variant->price,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            $order->items()->create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'price' => $variant->price,
                'qty' => 1,
            ]);

            return $order;
        });

        return response()->json([
            'message' => 'Snap token berhasil dibuat.',
            'snap_token' => $snapToken,
            'order_code' => $order->order_code,
            'status_url' => route('payments.status', $order->order_code),
        ]);
    }

    private function addresses(Request $request): Collection
    {
        return $request->user()
            ->addresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();
    }

    private function cartReviewItems(Request $request): array
    {
        $query = $request->user()
            ->cartItems()
            // PERBAIKAN 3: Pertahanan melawan data null akibat SoftDeletes
            ->whereHas('productVariant.product')
            ->with(['productVariant.product.images'])
            ->latest();

        if ($request->has('items')) {
            $itemIds = explode(',', $request->input('items'));
            $query->whereIn('id', $itemIds);
        }

        $items = $query->get()
            ->map(fn (CartItem $item) => [
                'id' => $item->id, 
                'product' => $item->productVariant->product,
                'variant' => $item->productVariant,
                'qty' => (int) $item->qty,
                'line_total' => $item->productVariant->price * (int) $item->qty,
            ]);

        return [$items, $items->sum('line_total')];
    }

    private function directReviewItems(int $variantId): array
    {
        $variant = ProductVariant::query()
            ->with(['product.images'])
            ->findOrFail($variantId);

        $items = collect([
            [
                'product' => $variant->product,
                'variant' => $variant,
                'qty' => 1,
                'line_total' => $variant->price,
            ],
        ]);

        return [$items, $variant->price];
    }

    private function requestedOrderItems(Request $request, array $data): Collection
    {
        if ($data['source'] === 'direct') {
            return collect([
                [
                    'variant_id' => (int) $data['variant_id'],
                    'qty' => 1,
                ],
            ]);
        }

        return $request->user()
            ->cartItems()
            ->whereIn('id', $data['selected_items'])
            ->get(['product_variant_id', 'qty'])
            ->map(fn (CartItem $item) => [
                'variant_id' => (int) $item->product_variant_id,
                'qty' => (int) $item->qty,
            ]);
    }

    private function generateOrderCode(): string
    {
        do {
            $code = 'ORD-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::query()->where('order_code', $code)->exists());

        return $code;
    }
}