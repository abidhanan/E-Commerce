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
            ['Admin','admin@toko.com','admin'],
            ['Editor','editor@toko.com','editor'],
            ['Finance','finance@toko.com','finance'],
            ['Staff','staff@toko.com','staff'],
            ['Customer','customer@toko.com','user'],
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