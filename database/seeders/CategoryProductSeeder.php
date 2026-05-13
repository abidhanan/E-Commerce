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
                'parent_slug' => null,
            ],
            [
                'name' => 'Fleece',
                'slug' => 'fleece',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => null,
            ],
            [
                'name' => 'Tops',
                'slug' => 'tops',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => null,
            ],
            [
                'name' => 'Pants',
                'slug' => 'pants',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => null,
            ],
            [
                'name' => 'Rain Jackets',
                'slug' => 'rain-jackets',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'jackets',
            ],
            [
                'name' => 'Insulated Jackets',
                'slug' => 'insulated-jackets',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'jackets',
            ],
            [
                'name' => 'Windbreakers',
                'slug' => 'windbreakers',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'jackets',
            ],
            [
                'name' => 'Softshell Jackets',
                'slug' => 'softshell-jackets',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'jackets',
            ],
            [
                'name' => 'Down Jackets',
                'slug' => 'down-jackets',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'jackets',
            ],
            [
                'name' => 'Pullover Fleece',
                'slug' => 'pullover-fleece',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'fleece',
            ],
            [
                'name' => 'Full-Zip Fleece',
                'slug' => 'full-zip-fleece',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'fleece',
            ],
            [
                'name' => 'Fleece Vests',
                'slug' => 'fleece-vests',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'fleece',
            ],
            [
                'name' => 'Base Layers',
                'slug' => 'base-layers',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'tops',
            ],
            [
                'name' => 'Running Tees',
                'slug' => 'running-tees',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'tops',
            ],
            [
                'name' => 'Long Sleeve Tops',
                'slug' => 'long-sleeve-tops',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'tops',
            ],
            [
                'name' => 'Trail Shirts',
                'slug' => 'trail-shirts',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'tops',
            ],
            [
                'name' => 'Trail Pants',
                'slug' => 'trail-pants',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'pants',
            ],
            [
                'name' => 'Hiking Pants',
                'slug' => 'hiking-pants',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'pants',
            ],
            [
                'name' => 'Joggers',
                'slug' => 'joggers',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'pants',
            ],
            [
                'name' => 'Shorts',
                'slug' => 'shorts',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'pants',
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => null,
            ],
            [
                'name' => 'Headwear',
                'slug' => 'headwear',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'accessories',
            ],
            [
                'name' => 'Gloves',
                'slug' => 'gloves',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'accessories',
            ],
            [
                'name' => 'Gaiters',
                'slug' => 'gaiters',
                'img' => 'categories/category-placeholder.svg',
                'parent_slug' => 'accessories',
            ],
        ];

        $categoryIds = [];

        foreach (collect($categories)->where('parent_slug', null) as $category) {
            $payload = $category;
            unset($payload['parent_slug']);
            $payload['parent_id'] = null;

            $model = CategoryProduct::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                $payload,
            );

            $categoryIds[$payload['slug']] = $model->id;
        }

        foreach (collect($categories)->whereNotNull('parent_slug') as $category) {
            $payload = $category;
            $parentSlug = $payload['parent_slug'];
            unset($payload['parent_slug']);
            $payload['parent_id'] = $categoryIds[$parentSlug] ?? null;

            $model = CategoryProduct::query()->updateOrCreate(
                ['slug' => $payload['slug']],
                $payload,
            );

            $categoryIds[$payload['slug']] = $model->id;
        }
    }
}
