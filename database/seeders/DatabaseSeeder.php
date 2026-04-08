<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder produk yang sudah kamu buat
        $this->call([
            ProductSeeder::class,
        ]);
    }
}