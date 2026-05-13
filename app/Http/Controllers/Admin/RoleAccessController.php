<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAccessController extends Controller
{
    public function index()
    {
        $this->syncConfiguredRolesAndPermissions();

        $groups = config('admin_permissions.groups', []);
        $roleOrder = array_flip(config('admin_permissions.roles', []));
        $roles = Role::query()
            ->whereIn('name', config('admin_permissions.roles', []))
            ->get()
            ->sortBy(fn (Role $role) => $roleOrder[$role->name] ?? PHP_INT_MAX)
            ->values();

        return view('Admin.RoleAccess.index', [
            'groups' => $groups,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request)
    {
        $this->syncConfiguredRolesAndPermissions();

        $permissionNames = $this->permissionNames();
        $roles = Role::query()
            ->whereIn('name', config('admin_permissions.roles', []))
            ->get();

        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['string', 'in:'.implode(',', $permissionNames)],
        ]);

        $submittedPermissions = $validated['permissions'] ?? [];

        foreach ($roles as $role) {
            if ($role->name === 'superadmin') {
                $role->syncPermissions($permissionNames);
                continue;
            }

            $role->syncPermissions($submittedPermissions[$role->name] ?? []);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('admin.role-access.index')
            ->with('success', 'Hak akses role berhasil diperbarui.');
    }

    private function syncConfiguredRolesAndPermissions(): void
    {
        $permissionNames = $this->permissionNames();

        foreach ($permissionNames as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        foreach (config('admin_permissions.roles', []) as $roleName) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            if ($roleName === 'superadmin' && $role->permissions()->count() === 0) {
                $role->syncPermissions($permissionNames);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function permissionNames(): array
    {
        return collect(config('admin_permissions.groups', []))
            ->flatMap(fn (array $group) => array_keys($group['permissions'] ?? []))
            ->unique()
            ->values()
            ->all();
    }
}
