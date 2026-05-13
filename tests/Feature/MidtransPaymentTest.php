<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class MidtransPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_callback_rejects_invalid_signature(): void
    {
        config(['midtrans.serverKey' => 'test-server-key']);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORDER-TEST-INVALID',
            'user_id' => $user->id,
            'gross_amount' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->postJson(route('midtrans.callback'), [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'invalid-signature',
        ]);

        $response->assertForbidden();
        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_midtrans_callback_marks_order_as_paid_on_settlement(): void
    {
        config(['midtrans.serverKey' => 'test-server-key']);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORDER-TEST-PAID',
            'user_id' => $user->id,
            'gross_amount' => 275000,
            'status' => 'pending',
        ]);

        $payload = [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '275000.00',
            'transaction_status' => 'settlement',
        ];

        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('midtrans.serverKey')
        );

        $response = $this->postJson(route('midtrans.callback'), $payload);

        $response->assertOk()->assertJson(['message' => 'ok']);
        $this->assertSame('paid', $order->fresh()->status);
    }

    public function test_verified_user_can_create_snap_checkout_payload(): void
    {
        config([
            'midtrans.serverKey' => 'Mid-server-valid-sandbox-key',
            'midtrans.clientKey' => 'Mid-client-valid-sandbox-key',
            'midtrans.isProduction' => false,
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Alpine Shield Jacket',
            'slug' => 'alpine-shield-jacket',
            'description' => 'Produk test checkout',
            'material' => 'Nylon',
            'gender' => 'unisex',
            'weight' => 400,
            'temperature' => 5,
            'intensity' => 'high',
            'insulation' => 70,
            'breathability' => 80,
            'is_active' => true,
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'ASH-JKT-M',
            'size' => 'M',
            'price' => 749000,
            'stock' => 10,
        ]);

        $snap = Mockery::mock('alias:Midtrans\Snap');
        $snap->shouldReceive('getSnapToken')
            ->once()
            ->andReturn('snap-token-test');

        $response = $this->actingAs($user)->postJson(route('checkout.snap', $variant->id));

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Snap token berhasil dibuat.',
                'snap_token' => 'snap-token-test',
            ]);

        $order = Order::query()->first();

        $this->assertNotNull($order);
        $this->assertSame($user->id, $order->user_id);
        $this->assertSame('snap-token-test', $order->snap_token);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'qty' => 1,
        ]);
    }
}
