<?php

namespace Database\Seeders;

use App\Models\CareGuide;
use Illuminate\Database\Seeder;

class CareGuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'question' => 'Bagaimana cara mencuci technical apparel?',
                'answer' => "Gunakan air dingin atau suhu rendah.\nPilih deterjen lembut dan hindari pelembut pakaian.",
                'position' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Bolehkah dikeringkan dengan mesin?',
                'answer' => "Bisa untuk beberapa bahan, tetapi suhu rendah lebih aman.\nJika ragu, jemur di tempat teduh agar struktur bahan tetap terjaga.",
                'position' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana menyimpan produk agar awet?',
                'answer' => "Simpan di tempat kering dan tidak lembap.\nPastikan produk benar-benar kering sebelum dilipat atau digantung.",
                'position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($guides as $guide) {
            CareGuide::query()->updateOrCreate(
                ['position' => $guide['position']],
                $guide,
            );
        }
    }
}
