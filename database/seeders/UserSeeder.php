<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Super Admin', 'superadmin@toko.com', 'superadmin', '081100000001', 'pria', '1990-01-15'],
            ['Admin', 'admin@toko.com', 'admin', '081100000002', 'wanita', '1993-03-21'],
            ['Editor', 'editor@toko.com', 'editor', '081100000003', 'pria', '1996-07-09'],
            ['Finance', 'finance@toko.com', 'finance', '081100000004', 'wanita', '1991-11-12'],
            ['Staff', 'staff@toko.com', 'staff', '081100000005', 'pria', '1998-05-30'],
            ['Customer', 'customer@toko.com', 'user', '081100000006', 'wanita', '2000-08-18'],
        ];

        foreach ($data as $u) {
            $user = User::updateOrCreate([
                'email' => $u[1],
            ], [
                'name' => $u[0],
                'phone' => $u[3],
                'gender' => $u[4],
                'date_of_birth' => $u[5],
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);

            $user->syncRoles([$u[2]]);
        }
    }
}
