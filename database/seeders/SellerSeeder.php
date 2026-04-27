<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SellerSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        for ($i = 1; $i <= 25; $i++) {
            DB::table('sellers')->updateOrInsert(
                ['id' => $i],
                [
                    'created_at' => $now->copy()->subDays(25 - $i),
                    'updated_at' => $now->copy()->subDays(25 - $i),
                ]
            );
        }
    }
}
