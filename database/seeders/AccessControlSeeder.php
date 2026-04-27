<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = Role::whereIn('name', $this->roleNames())->get()->keyBy('name');
        $users = User::whereIn('email', array_keys($this->userRoleMap()))->get()->keyBy('email');

        foreach ($this->roleNames() as $index => $roleName) {
            if (! isset($roles[$roleName])) {
                continue;
            }

            $permissionName = $this->permissionNames()[$index % count($this->permissionNames())];
            $roles[$roleName]->syncPermissions([$permissionName]);
        }

        foreach ($this->userRoleMap() as $email => $roleName) {
            if (! isset($users[$email], $roles[$roleName])) {
                continue;
            }

            $users[$email]->syncRoles([$roleName]);
        }

        $emails = array_keys($this->userRoleMap());
        foreach ($emails as $index => $email) {
            if (! isset($users[$email])) {
                continue;
            }

            $permissionName = $this->permissionNames()[($index + 3) % count($this->permissionNames())];
            $users[$email]->syncPermissions([$permissionName]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function roleNames(): array
    {
        return [
            'superadmin',
            'admin',
            'seller',
            'user',
            'guest',
            'content-manager',
            'catalog-manager',
            'support-agent',
            'warehouse-staff',
            'marketing-staff',
            'finance-staff',
            'moderator',
            'editor',
            'blogger',
            'photographer',
            'seo-specialist',
            'brand-manager',
            'customer-care',
            'qa-staff',
            'data-analyst',
            'merchandiser',
            'regional-manager',
            'campaign-manager',
            'loyalty-manager',
            'ops-manager',
        ];
    }

    private function permissionNames(): array
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

    private function userRoleMap(): array
    {
        return [
            'superadmin@toko.com' => 'superadmin',
            'admin@toko.com' => 'admin',
            'seller@toko.com' => 'seller',
            'customer@toko.com' => 'user',
            'guest@toko.com' => 'guest',
            'member01@toko.com' => 'content-manager',
            'member02@toko.com' => 'catalog-manager',
            'member03@toko.com' => 'support-agent',
            'member04@toko.com' => 'warehouse-staff',
            'member05@toko.com' => 'marketing-staff',
            'member06@toko.com' => 'finance-staff',
            'member07@toko.com' => 'moderator',
            'member08@toko.com' => 'editor',
            'member09@toko.com' => 'blogger',
            'member10@toko.com' => 'photographer',
            'member11@toko.com' => 'seo-specialist',
            'member12@toko.com' => 'brand-manager',
            'member13@toko.com' => 'customer-care',
            'member14@toko.com' => 'qa-staff',
            'member15@toko.com' => 'data-analyst',
            'member16@toko.com' => 'merchandiser',
            'member17@toko.com' => 'regional-manager',
            'member18@toko.com' => 'campaign-manager',
            'member19@toko.com' => 'loyalty-manager',
            'member20@toko.com' => 'ops-manager',
        ];
    }
}
