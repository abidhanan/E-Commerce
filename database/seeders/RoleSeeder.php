<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect(config('admin_permissions.groups', []))
            ->flatMap(fn (array $group) => array_keys($group['permissions'] ?? []))
            ->unique()
            ->values()
            ->all();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $roles = collect(config('admin_permissions.defaults', []))
            ->map(fn (array $rolePermissions) => in_array('*', $rolePermissions, true) ? $permissions : $rolePermissions)
            ->all();

        foreach ($roles as $role => $rolePermissions) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ])->syncPermissions($rolePermissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
