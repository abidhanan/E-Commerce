<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $placeholder = 'collections/collection-placeholder.svg';
        $now = now();

        $collections = [
            [
                'name' => 'Lebaran',
                'slug' => 'lebaran',
                'img' => $placeholder,
            ],
            [
                'name' => 'Winter Essentials',
                'slug' => 'winter-essentials',
                'img' => $placeholder,
            ],
            [
                'name' => 'Summer Vibes',
                'slug' => 'summer-vibes',
                'img' => $placeholder,
            ],
            ['name' => 'Autumn Layering', 'slug' => 'autumn-layering', 'img' => $placeholder],
            ['name' => 'Office Capsule', 'slug' => 'office-capsule', 'img' => $placeholder],
            ['name' => 'Weekend Casual', 'slug' => 'weekend-casual', 'img' => $placeholder],
            ['name' => 'Monochrome Edit', 'slug' => 'monochrome-edit', 'img' => $placeholder],
            ['name' => 'Resort Escape', 'slug' => 'resort-escape', 'img' => $placeholder],
            ['name' => 'Sport Mode', 'slug' => 'sport-mode', 'img' => $placeholder],
            ['name' => 'Denim Days', 'slug' => 'denim-days', 'img' => $placeholder],
            ['name' => 'Workwear Core', 'slug' => 'workwear-core', 'img' => $placeholder],
            ['name' => 'Urban Commuter', 'slug' => 'urban-commuter', 'img' => $placeholder],
            ['name' => 'Minimalist Picks', 'slug' => 'minimalist-picks', 'img' => $placeholder],
            ['name' => 'Party Night', 'slug' => 'party-night', 'img' => $placeholder],
            ['name' => 'Eid Special', 'slug' => 'eid-special', 'img' => $placeholder],
            ['name' => 'Rainy Season', 'slug' => 'rainy-season', 'img' => $placeholder],
            ['name' => 'Heritage Fit', 'slug' => 'heritage-fit', 'img' => $placeholder],
            ['name' => 'Street Season', 'slug' => 'street-season', 'img' => $placeholder],
            ['name' => 'Travel Ready', 'slug' => 'travel-ready', 'img' => $placeholder],
            ['name' => 'Relaxed Lounge', 'slug' => 'relaxed-lounge', 'img' => $placeholder],
            ['name' => 'Premium Tailoring', 'slug' => 'premium-tailoring', 'img' => $placeholder],
            ['name' => 'Coffee Run', 'slug' => 'coffee-run', 'img' => $placeholder],
            ['name' => 'Festival Edit', 'slug' => 'festival-edit', 'img' => $placeholder],
            ['name' => 'Daily Uniform', 'slug' => 'daily-uniform', 'img' => $placeholder],
            ['name' => 'Holiday Capsule', 'slug' => 'holiday-capsule', 'img' => $placeholder],
        ];

        foreach ($collections as $collection) {
            DB::table('collections')->updateOrInsert(
                ['slug' => $collection['slug']],
                $collection + [
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }
}
