<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::query()->where('email', 'superadmin@toko.com')->first();
        $admin = User::query()->where('email', 'admin@toko.com')->first();
        $editor = User::query()->where('email', 'editor@toko.com')->first();
        $finance = User::query()->where('email', 'finance@toko.com')->first();
        $staff = User::query()->where('email', 'staff@toko.com')->first();
        $product = Product::query()->where('slug', 'alpine-shield-jacket')->first();
        $post = Post::query()->where('slug', 'cara-memilih-jacket-untuk-cuaca-berubah')->first();

        $logs = [
            [
                'user_id' => $superadmin?->id,
                'event' => 'created',
                'model_type' => $product ? Product::class : null,
                'model_id' => $product?->id,
                'old_values' => null,
                'new_values' => $product ? [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'route' => 'admin.products.store',
                    'method' => 'POST',
                    'status_code' => 302,
                ] : null,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'macOS',
            ],
            [
                'user_id' => $editor?->id,
                'event' => 'published',
                'model_type' => $post ? Post::class : null,
                'model_id' => $post?->id,
                'old_values' => ['status' => 'draft'],
                'new_values' => [
                    'status' => 'published',
                    'route' => 'admin.blogs.publish',
                    'method' => 'GET',
                    'status_code' => 302,
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Laptop',
                'browser' => 'Firefox',
                'platform' => 'Windows',
            ],
            [
                'user_id' => $superadmin?->id,
                'event' => 'login',
                'model_type' => $superadmin ? User::class : null,
                'model_id' => $superadmin?->id,
                'old_values' => null,
                'new_values' => ['email' => $superadmin?->email],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Desktop',
                'browser' => 'Safari',
                'platform' => 'macOS',
            ],
            [
                'user_id' => $admin?->id,
                'event' => 'admin_update',
                'model_type' => $product ? Product::class : null,
                'model_id' => $product?->id,
                'old_values' => ['price' => 729000],
                'new_values' => [
                    'price' => 749000,
                    'route' => 'admin.products.update',
                    'method' => 'PUT',
                    'status_code' => 302,
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Desktop',
                'browser' => 'Chrome',
                'platform' => 'Windows',
            ],
            [
                'user_id' => $finance?->id,
                'event' => 'admin_update',
                'model_type' => null,
                'model_id' => null,
                'old_values' => ['status' => 'waiting_admin'],
                'new_values' => [
                    'status' => 'quoted',
                    'route' => 'admin.orders.quote',
                    'method' => 'PUT',
                    'status_code' => 302,
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Desktop',
                'browser' => 'Edge',
                'platform' => 'Windows',
            ],
            [
                'user_id' => $staff?->id,
                'event' => 'admin_post',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => [
                    'route' => 'admin.orders.status',
                    'method' => 'PATCH',
                    'status_code' => 302,
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder Dummy Browser',
                'device' => 'Tablet',
                'browser' => 'Chrome',
                'platform' => 'Android',
            ],
        ];

        foreach ($logs as $index => $log) {
            ActivityLog::query()->updateOrCreate(
                [
                    'user_id' => $log['user_id'],
                    'event' => $log['event'],
                    'model_type' => $log['model_type'],
                    'model_id' => $log['model_id'],
                    'ip_address' => $log['ip_address'],
                    'browser' => $log['browser'],
                ],
                array_merge($log, [
                    'created_at' => now()->subDays(6 - min($index, 6))->setTime(9 + $index, 15),
                    'updated_at' => now()->subDays(6 - min($index, 6))->setTime(9 + $index, 15),
                ]),
            );
        }
    }
}
