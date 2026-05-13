<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class OrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_order_history(): void
    {
        $response = $this->get(route('user.orders.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_see_their_order_history_progress(): void
    {
        $user = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($user, $variant, [
            'status' => 'quoted',
            'payment_url' => 'https://app.midtrans.com/payment-links/test',
            'quoted_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('user.orders.index'));

        $response
            ->assertOk()
            ->assertSee('Riwayat Pembelian')
            ->assertSee($order->order_code)
            ->assertSee('Menunggu Pembayaran')
            ->assertSee('Bayar');
    }

    public function test_user_can_open_their_order_detail(): void
    {
        $user = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($user, $variant, [
            'status' => 'processing',
            'admin_note' => 'Pesanan sedang disiapkan.',
        ]);

        $response = $this->actingAs($user)->get(route('user.orders.show', $order->order_code));

        $response
            ->assertOk()
            ->assertSee($order->order_code)
            ->assertSee('Diproses')
            ->assertSee('Alpine Shield Jacket')
            ->assertSee('Pesanan sedang disiapkan.');
    }

    public function test_user_cannot_open_other_users_order_detail(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($owner, $variant);

        $response = $this->actingAs($otherUser)->get(route('user.orders.show', $order->order_code));

        $response->assertNotFound();
    }

    public function test_shipped_order_is_completed_when_delivery_estimate_has_passed(): void
    {
        $user = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($user, $variant, [
            'status' => 'shipped',
            'shipped_at' => now()->subDays(3),
            'delivery_estimated_at' => now()->subMinute(),
        ]);

        $this->actingAs($user)
            ->get(route('user.orders.show', $order->order_code))
            ->assertOk()
            ->assertSee('Selesai');

        $this->assertSame('completed', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->completed_at);
    }

    public function test_user_can_mark_shipped_order_completed_and_submit_rating(): void
    {
        $user = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($user, $variant, [
            'status' => 'shipped',
            'shipped_at' => now(),
            'delivery_estimated_at' => now()->addDay(),
        ]);

        $this->actingAs($user)
            ->post(route('user.orders.complete', $order->order_code))
            ->assertRedirect(route('user.orders.show', $order->order_code));

        $this->assertSame('completed', $order->fresh()->status);

        $this->actingAs($user)
            ->post(route('user.orders.review', $order->order_code), [
                'rating' => 5,
                'comment' => 'Barang sesuai dan nyaman.',
            ])
            ->assertRedirect(route('user.orders.show', $order->order_code));

        $this->assertDatabaseHas('order_reviews', [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Barang sesuai dan nyaman.',
        ]);
    }

    public function test_user_can_submit_complaint_with_photo_and_admin_can_resolve_it(): void
    {
        Storage::fake('public');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $admin = User::factory()->create();
        Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $admin->assignRole('superadmin');

        $user = User::factory()->create();
        $variant = $this->createVariant();
        $order = $this->createOrder($user, $variant, [
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('user.orders.complaints.store', $order->order_code), [
                'subject' => 'Barang rusak',
                'message' => 'Jahitan lepas saat diterima.',
                'photos' => [
                    UploadedFile::fake()->image('damage.jpg'),
                ],
            ])
            ->assertRedirect(route('user.orders.show', $order->order_code));

        $complaint = $order->complaints()->with('photos')->first();

        $this->assertNotNull($complaint);
        $this->assertSame('submitted', $complaint->status);
        Storage::disk('public')->assertExists($complaint->photos->first()->path);

        $this->actingAs($admin)
            ->patch(route('admin.order-complaints.update', $complaint), [
                'status' => 'resolved',
                'admin_response' => 'Komplain selesai diproses.',
            ])
            ->assertRedirect(route('admin.order-complaints.show', $complaint));

        $complaint->refresh();
        $this->assertSame('resolved', $complaint->status);
        $this->assertNotNull($complaint->resolved_at);
    }

    private function createOrder(User $user, ProductVariant $variant, array $attributes = []): Order
    {
        $address = $user->addresses()->create([
            'label' => 'Rumah',
            'recipient_name' => 'Test User',
            'phone_number' => '08123456789',
            'full_address' => 'Jl. Test',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
        ]);

        $order = Order::create([
            'order_code' => 'ORD-HISTORY-'.uniqid(),
            'user_id' => $user->id,
            'address_id' => $address->id,
            'subtotal' => 200000,
            'shipping_cost' => 10000,
            'gross_amount' => 210000,
            'status' => 'waiting_admin',
            ...$attributes,
        ]);

        $order->items()->create([
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'price' => 100000,
            'qty' => 2,
        ]);

        return $order;
    }

    private function createVariant(): ProductVariant
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
            'description' => 'Produk test riwayat pesanan',
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
            'stock' => 10,
        ]);
    }
}
