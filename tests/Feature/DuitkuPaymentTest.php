<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\DuitkuService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DuitkuPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_duitku_callback_rejects_invalid_signature(): void
    {
        config([
            'duitku.merchant_code' => 'D1234',
            'duitku.api_key' => 'test-api-key',
        ]);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORDER-TEST-INVALID',
            'user_id' => $user->id,
            'gross_amount' => 150000,
            'status' => 'pending',
            'payment_gateway' => 'duitku',
            'payment_status' => 'pending',
        ]);

        $response = $this->postJson(route('payment.callback'), [
            'merchantCode' => 'D1234',
            'amount' => '150000',
            'merchantOrderId' => $order->order_code,
            'resultCode' => '00',
            'reference' => 'DUITKU-INVALID',
            'signature' => 'invalid-signature',
        ]);

        $response->assertStatus(400);
        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_duitku_callback_marks_order_as_paid_on_success(): void
    {
        config([
            'duitku.merchant_code' => 'D1234',
            'duitku.api_key' => 'test-api-key',
        ]);

        $user = User::factory()->create();
        $variant = $this->createVariant(stock: 5);
        $order = Order::create([
            'order_code' => 'ORDER-TEST-PAID',
            'user_id' => $user->id,
            'gross_amount' => 275000,
            'status' => 'pending',
            'payment_gateway' => 'duitku',
            'payment_reference' => 'DUITKU-PAID',
            'payment_status' => 'pending',
        ]);
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'price' => 275000,
            'qty' => 1,
        ]);

        $payload = [
            'merchantCode' => 'D1234',
            'amount' => '275000',
            'merchantOrderId' => $order->order_code,
            'productDetail' => 'Alpine Shield Jacket',
            'resultCode' => '00',
            'reference' => 'DUITKU-PAID',
        ];
        $payload['signature'] = app(DuitkuService::class)->callbackSignature(
            $payload['merchantCode'],
            $payload['amount'],
            $payload['merchantOrderId'],
            config('duitku.api_key'),
        );

        $response = $this->postJson(route('payment.callback'), $payload);

        $response->assertOk()->assertJson(['message' => 'ok']);
        $order->refresh();
        $this->assertSame('paid', $order->status);
        $this->assertSame('success', $order->payment_status);
        $this->assertSame('DUITKU-PAID', $order->payment_reference);
        $this->assertNotNull($order->paid_at);
        $this->assertSame(4, (int) $variant->fresh()->stock);
    }

    public function test_duitku_callback_is_idempotent_for_duplicate_success(): void
    {
        config([
            'duitku.merchant_code' => 'D1234',
            'duitku.api_key' => 'test-api-key',
        ]);

        $user = User::factory()->create();
        $variant = $this->createVariant(stock: 4);
        $order = Order::create([
            'order_code' => 'ORDER-TEST-DUPLICATE',
            'user_id' => $user->id,
            'gross_amount' => 100000,
            'status' => 'paid',
            'stock_deducted_at' => now(),
            'payment_gateway' => 'duitku',
            'payment_reference' => 'DUITKU-DUPLICATE',
            'payment_status' => 'success',
            'paid_at' => now(),
        ]);
        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'price' => 100000,
            'qty' => 1,
        ]);

        $payload = [
            'merchantCode' => 'D1234',
            'amount' => '100000',
            'merchantOrderId' => $order->order_code,
            'resultCode' => '00',
            'reference' => 'DUITKU-DUPLICATE',
        ];
        $payload['signature'] = app(DuitkuService::class)->callbackSignature(
            $payload['merchantCode'],
            $payload['amount'],
            $payload['merchantOrderId'],
            config('duitku.api_key'),
        );

        $this->postJson(route('payment.callback'), $payload)->assertOk();

        $this->assertSame(4, (int) $variant->fresh()->stock);
    }

    public function test_verified_user_can_create_duitku_checkout_invoice(): void
    {
        config([
            'duitku.merchant_code' => 'D1234',
            'duitku.api_key' => 'test-api-key',
            'duitku.sandbox' => true,
            'duitku.payment_method' => 'VC',
        ]);

        Http::fake([
            'sandbox.duitku.com/*' => Http::response([
                'statusCode' => '00',
                'statusMessage' => 'SUCCESS',
                'paymentUrl' => 'https://sandbox.duitku.com/checkout/test',
                'merchantCode' => 'D1234',
                'reference' => 'DUITKU-REF-TEST',
                'paymentMethod' => 'VC',
            ]),
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $address = $user->addresses()->create([
            'recipient_name' => 'Test User',
            'phone_number' => '08123456789',
            'full_address' => 'Jl. Test',
        ]);
        $variant = $this->createVariant(stock: 10);

        $response = $this->actingAs($user)->post(route('checkout.order'), [
            'source' => 'direct',
            'variant_id' => $variant->id,
            'address_id' => $address->id,
        ]);

        $response->assertRedirect('https://sandbox.duitku.com/checkout/test');

        $order = Order::query()->first();

        $this->assertNotNull($order);
        $this->assertSame('pending', $order->status);
        $this->assertSame('duitku', $order->payment_gateway);
        $this->assertSame('DUITKU-REF-TEST', $order->payment_reference);
        $this->assertSame('VC', $order->payment_method);
        $this->assertSame('https://sandbox.duitku.com/checkout/test', $order->payment_url);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'qty' => 1,
        ]);
    }

    public function test_return_url_does_not_update_database(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORDER-RETURN-TEST',
            'user_id' => $user->id,
            'gross_amount' => 100000,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $response = $this->get(route('payment.return', [
            'merchantOrderId' => $order->order_code,
            'reference' => 'DUITKU-RETURN',
            'resultCode' => '00',
        ]));

        $response->assertOk()->assertSee('Pembayaran Berhasil');
        $this->assertSame('pending', $order->fresh()->status);
        $this->assertSame('pending', $order->fresh()->payment_status);
    }

    private function createVariant(int $stock): ProductVariant
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets-'.uniqid(),
            'img' => 'categories/category-placeholder.svg',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Alpine Shield Jacket',
            'slug' => 'alpine-shield-jacket-'.uniqid(),
            'description' => 'Produk test payment',
            'material' => ['Nylon'],
            'gender' => 'unisex',
            'weight' => 400,
            'temperature' => 5,
            'intensity' => 'high',
            'insulation' => 70,
            'breathability' => 80,
            'is_active' => true,
        ]);

        return ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'ASH-JKT-M-'.uniqid(),
            'size' => 'M',
            'price' => 100000,
            'stock' => $stock,
        ]);
    }
}
