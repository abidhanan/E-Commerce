<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('users')->orderBy('id')->pluck('id')->values();
        $categoryIds = DB::table('category_blogs')->orderBy('id')->pluck('id')->values();
        $tagIds = DB::table('tag_blogs')->orderBy('id')->pluck('id')->values();
        $now = now();
        $thumbnail = 'blogs/blog-placeholder.svg';

        $titles = [
            '25 Ide Outfit Kantor yang Tetap Nyaman',
            'Panduan Layering untuk Musim Hujan',
            'Cara Memilih Blazer Sesuai Bentuk Tubuh',
            'Warna Netral yang Selalu Aman untuk Daily Look',
            'Tips Merawat Denim Agar Tahan Lama',
            'Mix and Match Outerwear untuk Hangout',
            'Inspirasi Capsule Wardrobe untuk Pemula',
            'Tren Street Style yang Lagi Naik Tahun Ini',
            'Aksesori Kecil yang Mengubah Keseluruhan Look',
            'Cara Styling Kaos Putih Supaya Tidak Membosankan',
            'Pilihan Outfit Lebaran yang Elegan dan Ringan',
            'Bagaimana Memilih Bahan Pakaian Premium',
            'Lookbook Akhir Pekan untuk Aktivitas Santai',
            'Panduan Belanja Fashion Saat Promo Besar',
            'Outfit Travel Ringan untuk Liburan Singkat',
            'Warna Musim Ini yang Mudah Dipadukan',
            'Cara Memakai Polo Shirt Lebih Modern',
            'Checklist Wardrobe Pria untuk Harian',
            'Checklist Wardrobe Wanita untuk Harian',
            '5 Celana Andalan yang Wajib Punya',
            'Di Balik Proses Kurasi Koleksi Baru',
            'Gaya Monokrom yang Tetap Terlihat Mewah',
            'Rekomendasi Tas Harian yang Fungsional',
            'Menjaga Tampilan Tetap Rapi Saat Mobilitas Tinggi',
            'Cerita Komunitas tentang Gaya Personal',
        ];

        foreach ($titles as $index => $title) {
            $status = $index % 3 === 0 ? 'draft' : 'published';
            $tagPair = [
                $tagIds[$index % $tagIds->count()],
                $tagIds[($index + 5) % $tagIds->count()],
            ];

            $content = $this->content($title, $index + 1);

            DB::table('posts')->updateOrInsert(
                ['slug' => Str::slug($title) . '-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $userIds[$index % $userIds->count()],
                    'category_id' => $categoryIds[$index % $categoryIds->count()],
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'tag_id' => json_encode($tagPair),
                    'excerpt' => Str::limit(strip_tags($content), 160),
                    'content' => $content,
                    'thumbnail' => $thumbnail,
                    'status' => $status,
                    'published_at' => $status === 'published' ? $now->copy()->subDays(25 - $index) : null,
                    'created_at' => $now->copy()->subDays(25 - $index),
                    'updated_at' => $now->copy()->subDays(25 - $index),
                ]
            );
        }
    }

    private function content(string $title, int $number): string
    {
        return '<h2>' . e($title) . '</h2>'
            . '<p>Artikel dummy ke-' . $number . ' ini membahas inspirasi styling yang mudah diterapkan untuk aktivitas harian, kerja, sampai akhir pekan.</p>'
            . '<p>Kami fokus pada kombinasi warna, siluet, dan bahan supaya tampilan tetap rapi tanpa terasa berlebihan.</p>'
            . '<blockquote><div>Mulai dari item basic yang sudah ada di lemari, lalu bangun look secara bertahap.</div></blockquote>'
            . '<ul><li>Pilih satu item utama</li><li>Tambahkan layer seperlunya</li><li>Tutup dengan aksesori sederhana</li></ul>';
    }
}
