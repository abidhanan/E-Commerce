<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = DB::table('categories')
            ->whereNotNull('parent_id')
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $collectionIds = DB::table('collections')
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $images = [
            '1.avif',
            '2.jpg',
            '3.jpg',
            '4.jpg',
            '5.jpg',
            'chanel-1.jpg',
            'chanel-2.png',
            'chanel-3.png',
            'chanel-4.png',
            'chanel-5.png',
            'chanel.png',
            'firebird-track-top.webp',
            'louis-vuiton.png',
            'prada.png',
            'versace.png',
        ];

        $names = [
            'Chesterfield Coat',
            'Louis Fleece Zip',
            'Prada Knit Top',
            'Versace Utility Pants',
            'Dior Tailored Blazer',
            'Daily Graphic Tee',
            'Bomber Flight Jacket',
            'Minimal Polo Shirt',
            'Weekend Chino Pants',
            'Soft Sherpa Hoodie',
            'Runway Trench Coat',
            'Urban Cargo Jogger',
            'Lounge Knit Pullover',
            'Classic Denim Fit',
            'Relaxed Short Pants',
            'Heritage Windbreaker',
            'Monochrome Work Shirt',
            'Signature Crop Top',
            'Studio Sweatshirt',
            'Streetwear Belt Bag',
            'Canvas Crossbody Bag',
            'Wool Blend Scarf',
            'Leather Accent Belt',
            'Logo Cap Essential',
            'Travel Layer Jacket',
        ];

        $now = now();

        foreach ($names as $index => $name) {
            DB::table('products')->updateOrInsert(
                ['name' => $name],
                [
                    'category_id' => $categoryIds[$index % $categoryIds->count()],
                    'collection_id' => $collectionIds[$index % $collectionIds->count()],
                    'price' => 149000 + (($index + 1) * 37000),
                    'image' => $images[$index % count($images)],
                    'rating' => 3 + ($index % 3),
                    'created_at' => $now->copy()->subDays(25 - $index),
                    'updated_at' => $now->copy()->subDays(25 - $index),
                ]
            );
        }
    }
}
