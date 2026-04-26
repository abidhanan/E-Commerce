<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryProduct;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
