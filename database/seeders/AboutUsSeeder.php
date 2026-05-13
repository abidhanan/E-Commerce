<?php

namespace Database\Seeders;

use App\Models\Aboutus;
use Illuminate\Database\Seeder;

class AboutUsSeeder extends Seeder
{
    public function run(): void
    {
        Aboutus::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'About Gloaming Imagine',
                'content' => implode('', [
                    '<p>Gloaming Imagine dibangun untuk orang-orang yang bergerak cepat, peduli detail, dan ingin pakaian yang tetap terasa tepat dipakai dari perjalanan pagi sampai hari berakhir.</p>',
                    '<p>Kami melihat apparel bukan hanya soal tampilan, tapi tentang ritme. Potongan yang bersih, material yang terasa benar saat dipakai, dan koleksi yang mudah dipadukan adalah fondasi dari setiap keputusan kami.</p>',
                    '<h2>What We Value</h2>',
                    '<ul>',
                    '<li>Kenyamanan yang terasa ringan dan tidak berisik secara visual.</li>',
                    '<li>Fungsi yang relevan untuk perjalanan, komuter, dan rutinitas aktif.</li>',
                    '<li>Detail yang rapi, tahan lama, dan mudah dipakai berulang.</li>',
                    '</ul>',
                    '<blockquote>Produk yang baik seharusnya membantu gerak, bukan menuntut perhatian berlebihan.</blockquote>',
                    '<h2>How We Work</h2>',
                    '<p>Kami mengembangkan koleksi dengan pendekatan yang tenang: mengurangi hal yang tidak perlu, menjaga kualitas material, dan memastikan setiap rilisan tetap punya alasan yang jelas untuk hadir.</p>',
                    '<p>Lewat halaman ini, tim admin bisa terus memperbarui narasi brand tanpa perlu mengubah template user.</p>',
                ]),
            ],
        );
    }
}
