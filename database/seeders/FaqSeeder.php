<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'id' => 1,
                'question' => 'Bagaimana cara mengetahui ukuran yang paling cocok?',
                'answer' => "Mulai dari size guide pada halaman produk.\nJika Anda berada di antara dua ukuran, pilih berdasarkan fit yang diinginkan: ukuran lebih kecil untuk fit lebih rapat, ukuran lebih besar untuk feel yang lebih santai.",
                'position' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'question' => 'Apakah stok yang tampil di website selalu terbaru?',
                'answer' => "Ya. Stok di halaman produk mengikuti data yang tersedia saat ini.\nJika sebuah ukuran atau varian tidak bisa dipilih, biasanya stoknya sudah habis atau belum tersedia kembali.",
                'position' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'question' => 'Berapa lama pesanan diproses setelah pembayaran berhasil?',
                'answer' => "Pesanan umumnya diproses dalam 1-2 hari kerja.\nSaat pesanan sudah dikirim, informasi status dan pengiriman akan mengikuti update dari sistem order Anda.",
                'position' => 3,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'question' => 'Bagaimana perawatan terbaik untuk produk technical apparel?',
                'answer' => "Cuci dengan air dingin atau suhu rendah, gunakan deterjen lembut, dan hindari pelembut pakaian.\nJemur di tempat teduh agar bentuk dan performa material tetap terjaga.",
                'position' => 4,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'question' => 'Apakah saya bisa menghubungi tim untuk pertanyaan sebelum checkout?',
                'answer' => "Bisa. Anda dapat menggunakan kanal kontak yang tersedia di customer care untuk pertanyaan seputar ukuran, produk, atau pesanan sebelum melakukan checkout.",
                'position' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::query()->updateOrCreate(
                ['id' => $faq['id']],
                [
                    'answer' => $faq['answer'],
                    'question' => $faq['question'],
                    'position' => $faq['position'],
                    'is_active' => $faq['is_active'],
                ],
            );
        }
    }
}
