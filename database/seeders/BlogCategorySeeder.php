<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fashion Trends',
            'Styling Tips',
            'Brand Stories',
            'Seasonal Picks',
            'Capsule Wardrobe',
            'Office Looks',
            'Weekend Outfit',
            'Street Style',
            'Minimalist Fashion',
            'Luxury Edit',
            'Color Guide',
            'Fabric Guide',
            'Care and Maintenance',
            'Accessorizing',
            'Travel Outfit',
            'Event Dressing',
            'Sustainable Fashion',
            'Sneaker Culture',
            'Menswear Notes',
            'Womenswear Notes',
            'Behind the Scene',
            'Lookbook',
            'Shopping Guide',
            'Trend Forecast',
            'Community Stories',
        ];

        $now = now();

        foreach ($categories as $index => $name) {
            DB::table('category_blogs')->updateOrInsert(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'created_at' => $now->copy()->subDays(25 - $index),
                    'updated_at' => $now->copy()->subDays(25 - $index),
                ]
            );
        }
    }
}
