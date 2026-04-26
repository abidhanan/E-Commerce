<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Super Admin','superadmin@toko.com','superadmin'],
            ['Admin', 'admin@toko.com', 'admin'],
            ['Seller','seller@toko.com','seller'],
            ['Customer','customer@toko.com','user'],
            ['Guest','guest@toko.com','guest'],
        ];

        foreach ($data as $u) {
            $user = User::create([
                'name' => $u[0],
                'email' => $u[1],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole($u[2]);
        }
    }
}