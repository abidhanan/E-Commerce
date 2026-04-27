<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagBlogSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'fashion',
            'lookbook',
            'streetwear',
            'minimal',
            'formal',
            'casual',
            'outerwear',
            'accessories',
            'layering',
            'summer',
            'winter',
            'ramadan',
            'denim',
            'workwear',
            'capsule',
            'premium',
            'sale',
            'editorial',
            'trend',
            'wardrobe',
            'styling',
            'community',
            'travel',
            'colorways',
            'essentials',
        ];

        $now = now();

        foreach ($tags as $index => $tag) {
            DB::table('tag_blogs')->updateOrInsert(
                ['name' => $tag],
                [
                    'name' => $tag,
                    'created_at' => $now->copy()->subDays(25 - $index),
                    'updated_at' => $now->copy()->subDays(25 - $index),
                ]
            );
        }
    }
}
