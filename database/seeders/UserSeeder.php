<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $password = Hash::make('password123');
        $now = now();

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@toko.com',
                'phone' => '081200000001',
                'dob' => '1988-01-15',
                'gender' => 'female',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@toko.com',
                'phone' => '081200000002',
                'dob' => '1990-03-22',
                'gender' => 'male',
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@toko.com',
                'phone' => '081200000003',
                'dob' => '1992-05-11',
                'gender' => 'female',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@toko.com',
                'phone' => '081200000004',
                'dob' => '1998-07-08',
                'gender' => 'male',
            ],
            [
                'name' => 'Guest',
                'email' => 'guest@toko.com',
                'phone' => '081200000005',
                'dob' => '2000-09-19',
                'gender' => 'female',
            ],
        ];

        for ($i = 1; $i <= 20; $i++) {
            $users[] = [
                'name' => $faker->name(),
                'email' => sprintf('member%02d@toko.com', $i),
                'phone' => '08123' . str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                'dob' => $faker->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
                'gender' => $i % 2 === 0 ? 'male' : 'female',
            ];
        }

        foreach ($users as $index => $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'phone' => $user['phone'],
                    'dob' => $user['dob'],
                    'gender' => $user['gender'],
                    'password' => $password,
                    'remember_token' => Str::random(10),
                    'email_verified_at' => $now,
                    'created_at' => $now->copy()->subDays(30 - $index),
                    'updated_at' => $now->copy()->subDays(30 - $index),
                ]
            );
        }
    }
}
