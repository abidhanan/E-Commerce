<?php

namespace Database\Seeders;

use App\Models\CategoryProduct;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Jackets',
                'slug' => 'jackets',
                'img' => 'categories/category-placeholder.svg',
                'parent_id' => null,
            ],
            [
                'name' => 'Fleece',
                'slug' => 'fleece',
                'img' => 'categories/category-placeholder.svg',
                'parent_id' => null,
            ],
            [
                'name' => 'Tops',
                'slug' => 'tops',
                'img' => 'categories/category-placeholder.svg',
                'parent_id' => null,
            ],
            [
                'name' => 'Pants',
                'slug' => 'pants',
                'img' => 'categories/category-placeholder.svg',
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $category) {
            CategoryProduct::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
