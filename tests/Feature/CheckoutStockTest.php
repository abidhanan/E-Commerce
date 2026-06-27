<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class CheckoutStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_cart_rejects_quantity_above_variant_stock(): void
    {
        $user = User::factory()->create();
        $variant = $this->createVariant(stock: 1);

        $response = $this->actingAs($user)->postJson(route('cart.store'), [
            'variant_id' => $variant->id,
            'qty' => 2,
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
            'product_variant_id' => $variant->id,
        ]);
    }

    public function test_checkout_rejects_cart_item_when_stock_is_not_enough(): void
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create([
            'recipient_name' => 'Test User',
            'phone_number' => '08123456789',
            'full_address' => 'Jl. Test',
        ]);
        $variant = $this->createVariant(stock: 1);

        $user->cartItems()->create([
            'product_variant_id' => $variant->id,
            'qty' => 2,
        ]);

        $cartItemId = $user->cartItems()->value('id');

        $response = $this->actingAs($user)->post(route('checkout.order'), [
            'address_id' => $address->id,
            'source' => 'cart',
            'selected_items' => [$cartItemId],
        ]);

        $response->assertSessionHasErrors('stock');
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);
    }

    public function test_checkout_review_page_renders_cart_preview_and_address_switch(): void
    {
        $user = User::factory()->create();
        $user->addresses()->create([
            'label' => 'Rumah',
            'recipient_name' => 'Test User',
            'phone_number' => '08123456789',
            'full_address' => 'Jl. Test',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'is_primary' => true,
        ]);
        $variant = $this->createVariant(stock: 4);

        $user->cartItems()->create([
            'product_variant_id' => $variant->id,
            'qty' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response
            ->assertOk()
            ->assertSee('Order Summary')
            ->assertSee('Change')
            ->assertSee('Alpine Shield Jacket');
    }

    public function test_checkout_creates_pending_duitku_order_from_cart_and_sends_email(): void
    {
        Notification::fake();
        $this->fakeDuitkuInvoice();

        $user = User::factory()->create();
        $address = $user->addresses()->create([
            'label' => 'Rumah',
            'recipient_name' => 'Test User',
            'phone_number' => '08123456789',
            'full_address' => 'Jl. Test',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'is_primary' => true,
        ]);
        $variant = $this->createVariant(stock: 4);

        $cartItem = $user->cartItems()->create([
            'product_variant_id' => $variant->id,
            'qty' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.order'), [
            'address_id' => $address->id,
            'source' => 'cart',
            'customer_note' => 'Tolong cek alamat.',
            'selected_items' => [$cartItem->id],
        ]);

        $order = Order::query()->first();

        $this->assertNotNull($order);
        $response->assertRedirect('https://sandbox.duitku.com/checkout/test');
        $this->assertSame('pending', $order->status);
        $this->assertSame('duitku', $order->payment_gateway);
        $this->assertSame('DUITKU-REF-TEST', $order->payment_reference);
        $this->assertSame(200000.0, (float) $order->subtotal);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_variant_id' => $variant->id,
            'qty' => 2,
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
        ]);
        Notification::assertSentTo($user, OrderCreatedNotification::class);
    }

    public function test_admin_quote_deducts_stock_once_and_cancel_restores_it(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = User::factory()->create();
        Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin->assignRole('superadmin');

        $customer = User::factory()->create();
        $variant = $this->createVariant(stock: 10);
        $order = Order::create([
            'order_code' => 'ORDER-STOCK-TEST',
            'user_id' => $customer->id,
            'subtotal' => 200000,
            'gross_amount' => 200000,
            'status' => 'waiting_admin',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'price' => 100000,
            'qty' => 2,
        ]);

        $quotePayload = [
            'shipping_cost' => 10000,
            'gross_amount' => 210000,
            'admin_note' => 'OK',
        ];

        $this->actingAs($admin)
            ->put(route('admin.orders.quote', $order), $quotePayload)
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(8, (int) $variant->fresh()->stock);
        $this->assertNotNull($order->fresh()->stock_deducted_at);

        $this->actingAs($admin)
            ->put(route('admin.orders.quote', $order), $quotePayload)
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(8, (int) $variant->fresh()->stock);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => 'cancelled'])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame(10, (int) $variant->fresh()->stock);
        $this->assertNull($order->fresh()->stock_deducted_at);
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
            'description' => 'Produk test checkout',
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

    private function fakeDuitkuInvoice(): void
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
                'reference' => 'DUITKU-REF-TEST',
                'paymentMethod' => 'VC',
            ]),
        ]);
    }
}
