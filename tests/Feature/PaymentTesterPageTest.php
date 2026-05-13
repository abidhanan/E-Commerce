<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTesterPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_payment_tester_page(): void
    {
        $response = $this->get(route('payments.tester'));

        $response->assertRedirect(route('login'));
    }

    public function test_verified_user_can_open_payment_tester_page(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get(route('payments.tester'));

        $response
            ->assertOk()
            ->assertSee('Dashboard beli sederhana untuk tester pembayaran.')
            ->assertSee('Belum ada produk aktif');
    }
}
