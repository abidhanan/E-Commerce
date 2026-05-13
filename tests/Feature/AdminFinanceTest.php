<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminFinanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_admin_finance_dashboard_uses_order_table_totals(): void
    {
        $admin = $this->userWithRole('admin');
        $customer = User::factory()->create();

        Order::create([
            'order_code' => 'ORD-PAID-FINANCE',
            'user_id' => $customer->id,
            'subtotal' => 250000,
            'shipping_cost' => 25000,
            'gross_amount' => 275000,
            'status' => 'paid',
        ]);

        Order::create([
            'order_code' => 'ORD-WAITING-FINANCE',
            'user_id' => $customer->id,
            'subtotal' => 100000,
            'gross_amount' => 100000,
            'status' => 'waiting_admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.finance.index'));

        $response
            ->assertOk()
            ->assertSee('Dashboard Finance')
            ->assertSee('Rp 275.000')
            ->assertSee('Menunggu Quote')
            ->assertSee('ORD-WAITING-FINANCE');
    }

    public function test_finance_area_is_limited_to_superadmin_admin_and_finance_roles(): void
    {
        foreach (['superadmin', 'admin', 'finance'] as $role) {
            $response = $this->actingAs($this->userWithRole($role))->get(route('admin.finance.index'));

            $response->assertOk();
        }

        $response = $this->actingAs($this->userWithRole('staff'))->get(route('admin.finance.index'));

        $response->assertForbidden();
    }

    public function test_admin_dashboard_uses_database_metrics(): void
    {
        $admin = $this->userWithRole('admin');
        $customer = User::factory()->create();

        ActivityLog::create([
            'user_id' => $admin->id,
            'event' => 'login',
        ]);

        Order::create([
            'order_code' => 'ORD-DASHBOARD-PAID',
            'user_id' => $customer->id,
            'gross_amount' => 120000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertSee('Ringkasan operasional admin')
            ->assertSee('Rp 120.000')
            ->assertSee('ORD-DASHBOARD-PAID')
            ->assertSee('Login unik 30 hari terakhir');
    }

    private function userWithRole(string $roleName): User
    {
        Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $user->assignRole($roleName);

        return $user;
    }
}
