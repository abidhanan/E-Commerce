<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $placeholder = 'categories/category-placeholder.svg';
        $now = now();

        $parents = [
            [
                'name' => 'Jackets',
                'slug' => 'jackets',
                'img' => $placeholder,
                'parent_id' => null,
            ],
            [
                'name' => 'Fleece',
                'slug' => 'fleece',
                'img' => $placeholder,
                'parent_id' => null,
            ],
            [
                'name' => 'Tops',
                'slug' => 'tops',
                'img' => $placeholder,
                'parent_id' => null,
            ],
            [
                'name' => 'Pants',
                'slug' => 'pants',
                'img' => $placeholder,
                'parent_id' => null,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'img' => $placeholder,
                'parent_id' => null,
            ],
        ];

        foreach ($parents as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category + [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $parentIds = DB::table('categories')
            ->whereIn('slug', collect($parents)->pluck('slug'))
            ->pluck('id', 'slug');

        $children = [
            ['name' => 'Bomber Jackets', 'slug' => 'bomber-jackets', 'parent_slug' => 'jackets'],
            ['name' => 'Trench Coats', 'slug' => 'trench-coats', 'parent_slug' => 'jackets'],
            ['name' => 'Blazers', 'slug' => 'blazers', 'parent_slug' => 'jackets'],
            ['name' => 'Windbreakers', 'slug' => 'windbreakers', 'parent_slug' => 'jackets'],
            ['name' => 'Sherpa Fleece', 'slug' => 'sherpa-fleece', 'parent_slug' => 'fleece'],
            ['name' => 'Zip Hoodies', 'slug' => 'zip-hoodies', 'parent_slug' => 'fleece'],
            ['name' => 'Sweatshirts', 'slug' => 'sweatshirts', 'parent_slug' => 'fleece'],
            ['name' => 'Knit Pullovers', 'slug' => 'knit-pullovers', 'parent_slug' => 'fleece'],
            ['name' => 'Graphic Tees', 'slug' => 'graphic-tees', 'parent_slug' => 'tops'],
            ['name' => 'Shirts', 'slug' => 'shirts', 'parent_slug' => 'tops'],
            ['name' => 'Crop Tops', 'slug' => 'crop-tops', 'parent_slug' => 'tops'],
            ['name' => 'Polos', 'slug' => 'polos', 'parent_slug' => 'tops'],
            ['name' => 'Denim', 'slug' => 'denim', 'parent_slug' => 'pants'],
            ['name' => 'Chinos', 'slug' => 'chinos', 'parent_slug' => 'pants'],
            ['name' => 'Joggers', 'slug' => 'joggers', 'parent_slug' => 'pants'],
            ['name' => 'Shorts', 'slug' => 'shorts', 'parent_slug' => 'pants'],
            ['name' => 'Hats', 'slug' => 'hats', 'parent_slug' => 'accessories'],
            ['name' => 'Bags', 'slug' => 'bags', 'parent_slug' => 'accessories'],
            ['name' => 'Belts', 'slug' => 'belts', 'parent_slug' => 'accessories'],
            ['name' => 'Scarves', 'slug' => 'scarves', 'parent_slug' => 'accessories'],
        ];

        foreach ($children as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'img' => $placeholder,
                    'parent_id' => $parentIds[$category['parent_slug']] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
