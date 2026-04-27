<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->permissions() as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }
    }

    private function permissions(): array
    {
        return [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'collections.view',
            'collections.create',
            'collections.update',
            'collections.delete',
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'blogs.view',
            'blogs.create',
            'blogs.update',
            'blogs.delete',
            'tags.manage',
            'comments.moderate',
            'reports.view',
            'settings.manage',
        ];
    }
}
