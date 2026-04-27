<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->roles() as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);
        }
    }

    private function roles(): array
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
}
