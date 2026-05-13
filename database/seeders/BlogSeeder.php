<?php

namespace Database\Seeders;

use App\Models\CategoryBlog;
use App\Models\Post;
use App\Models\TagBlog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Gear Guide', 'slug' => 'gear-guide'],
            ['name' => 'Outdoor Tips', 'slug' => 'outdoor-tips'],
            ['name' => 'Brand Story', 'slug' => 'brand-story'],
        ])->mapWithKeys(function (array $category) {
            $model = CategoryBlog::query()->updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']],
            );

            return [$category['slug'] => $model];
        });

        $tags = collect(['Layering', 'Hiking', 'Commuting', 'Care', 'Sustainability'])
            ->mapWithKeys(function (string $tag) {
                $model = TagBlog::query()->updateOrCreate(
                    ['name' => $tag],
                    ['name' => $tag],
                );

                return [$tag => $model];
            });

        $author = User::query()->where('email', 'editor@toko.com')->first()
            ?? User::query()->where('email', 'superadmin@toko.com')->first();

        if (! $author) {
            return;
        }

        $posts = [
            [
                'category_slug' => 'gear-guide',
                'title' => 'Cara Memilih Jacket untuk Cuaca Berubah',
                'slug' => 'cara-memilih-jacket-untuk-cuaca-berubah',
                'tags' => ['Layering', 'Hiking'],
                'excerpt' => 'Panduan cepat memilih shell jacket, windbreaker, dan fleece sesuai kebutuhan.',
                'content' => '<p>Pilih outerwear dari kombinasi cuaca, intensitas aktivitas, dan durasi perjalanan.</p><p>Untuk hujan ringan, shell jacket ringan sudah cukup. Untuk udara dingin, tambahkan fleece sebagai mid-layer.</p>',
                'thumbnail' => 'blogs/jacket-guide.svg',
                'status' => 'published',
                'published_at' => now()->subDays(12),
            ],
            [
                'category_slug' => 'outdoor-tips',
                'title' => 'Layering Ringan untuk Hiking Pagi',
                'slug' => 'layering-ringan-untuk-hiking-pagi',
                'tags' => ['Layering', 'Hiking'],
                'excerpt' => 'Mulai dari base layer, mid-layer, sampai shell yang gampang dibawa.',
                'content' => '<p>Layering yang baik membuat suhu tubuh stabil tanpa membawa terlalu banyak barang.</p><p>Gunakan base layer breathable, fleece tipis, dan windbreaker packable.</p>',
                'thumbnail' => 'blogs/morning-hike.svg',
                'status' => 'published',
                'published_at' => now()->subDays(8),
            ],
            [
                'category_slug' => 'gear-guide',
                'title' => 'Merawat Bahan Quick-Dry agar Awet',
                'slug' => 'merawat-bahan-quick-dry-agar-awet',
                'tags' => ['Care', 'Commuting'],
                'excerpt' => 'Tips mencuci dan menyimpan pakaian teknikal supaya performanya tetap bagus.',
                'content' => '<p>Cuci dengan deterjen lembut, hindari pelembut pakaian, dan keringkan di area teduh.</p><p>Cara ini membantu serat tetap ringan dan cepat kering.</p>',
                'thumbnail' => 'blogs/quick-dry-care.svg',
                'status' => 'draft',
                'published_at' => null,
            ],
            [
                'category_slug' => 'brand-story',
                'title' => 'Kenapa Kami Memakai Material Daur Ulang',
                'slug' => 'kenapa-kami-memakai-material-daur-ulang',
                'tags' => ['Sustainability'],
                'excerpt' => 'Cerita singkat tentang pilihan material dan proses produksi yang lebih bertanggung jawab.',
                'content' => '<p>Material daur ulang membantu mengurangi limbah tanpa mengorbankan performa produk.</p><p>Kami memilih bahan berdasarkan ketahanan, kenyamanan, dan dampaknya.</p>',
                'thumbnail' => 'blogs/recycled-material.svg',
                'status' => 'published',
                'published_at' => now()->subDays(4),
            ],
        ];

        foreach ($posts as $item) {
            $tagIds = collect($item['tags'])
                ->map(fn (string $tag) => $tags[$tag]->id ?? null)
                ->filter()
                ->values()
                ->all();

            $post = Post::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'user_id' => $author->id,
                    'category_id' => $categories[$item['category_slug']]->id,
                    'tag_id' => $tagIds,
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'thumbnail' => $item['thumbnail'],
                    'status' => $item['status'],
                    'published_at' => $item['published_at'],
                ],
            );

            if ($post->status === 'published') {
                $this->seedComments($post);
            }
        }
    }

    private function seedComments(Post $post): void
    {
        $comments = [
            [
                'name' => 'Bima Pratama',
                'email' => 'bima@example.com',
                'content' => 'Artikelnya jelas, jadi lebih gampang bedain shell jacket dan windbreaker.',
            ],
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia@example.com',
                'content' => 'Bagian layering-nya kepake banget buat rencana naik gunung minggu depan.',
            ],
        ];

        $customer = User::query()->where('email', 'customer@toko.com')->first();

        foreach ($comments as $comment) {
            DB::table('comments')->updateOrInsert(
                [
                    'post_id' => $post->id,
                    'email' => $comment['email'],
                ],
                [
                    'user_id' => $customer?->id,
                    'name' => $comment['name'],
                    'content' => $comment['content'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
