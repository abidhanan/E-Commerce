<?php

namespace Database\Seeders;

use App\Models\CrashReplacement;
use Illuminate\Database\Seeder;

class CrashReplacementSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'question' => 'Kapan saya bisa mengajukan crash replacement?',
                'answer' => "Ajukan saat produk mengalami kerusakan karena insiden saat pemakaian yang masih masuk kebijakan layanan.\nSertakan nomor order dan foto pendukung.",
                'position' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apa saja yang perlu disiapkan?',
                'answer' => "Siapkan detail order, foto kerusakan, dan penjelasan singkat kronologi.\nTim akan menggunakan data ini untuk proses review awal.",
                'position' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama proses review?',
                'answer' => "Review awal biasanya dilakukan dalam beberapa hari kerja.\nJika disetujui, tim akan memberi instruksi lanjutan untuk penggantian atau solusi yang tersedia.",
                'position' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            CrashReplacement::query()->updateOrCreate(
                ['position' => $item['position']],
                $item,
            );
        }
    }
}
