<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_superadmin_can_review_staff_performance_except_user_role(): void
    {
        $superadmin = $this->userWithRole('superadmin', 'superadmin@example.test', 'Super Admin');
        $admin = $this->userWithRole('admin', 'admin@example.test', 'Admin Staff');
        $editor = $this->userWithRole('editor', 'editor@example.test', 'Editor Staff');
        $customer = $this->userWithRole('user', 'customer@example.test', 'Customer User');

        ActivityLog::create([
            'user_id' => $admin->id,
            'event' => 'admin_update',
            'new_values' => ['route' => 'admin.products.update', 'method' => 'PUT', 'status_code' => 302],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => $editor->id,
            'event' => 'published',
            'new_values' => ['title' => 'Blog post'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => $customer->id,
            'event' => 'login',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($superadmin)->get(route('admin.performance.index'));

        $response
            ->assertOk()
            ->assertSee('Kinerja Tim')
            ->assertSee('Admin Staff')
            ->assertSee('Editor Staff')
            ->assertDontSee('Customer User')
            ->assertSee('admin.products.update');
    }

    public function test_only_superadmin_can_open_performance_pages(): void
    {
        $admin = $this->userWithRole('admin', 'admin@example.test', 'Admin Staff');

        $response = $this->actingAs($admin)->get(route('admin.performance.index'));

        $response->assertForbidden();
    }

    public function test_admin_write_actions_are_recorded_for_performance_tracking(): void
    {
        $superadmin = $this->userWithRole('superadmin', 'superadmin@example.test', 'Super Admin');

        $response = $this->actingAs($superadmin)
            ->put(route('admin.role-access.update'), ['permissions' => []]);

        $response->assertRedirect(route('admin.role-access.index'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $superadmin->id,
            'event' => 'admin_update',
        ]);

        $this->assertSame(
            'admin.role-access.update',
            data_get(ActivityLog::query()->where('event', 'admin_update')->first()?->new_values, 'route'),
        );
    }

    private function userWithRole(string $roleName, string $email, string $name): User
    {
        Role::firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($roleName);

        return $user;
    }
}
