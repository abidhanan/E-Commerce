<?php

namespace Database\Seeders;

use App\Models\Collections;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Lebaran',
                'slug' => 'lebaran',
                'img' => 'collections/collection-placeholder.svg',
            ],
            [
                'name' => 'Winter Essentials',
                'slug' => 'winter-essentials',
                'img' => 'collections/collection-placeholder.svg',
            ],
            [
                'name' => 'Summer Vibes',
                'slug' => 'summer-vibes',
                'img' => 'collections/collection-placeholder.svg',
            ],
        ];

        foreach ($collections as $collection) {
            Collections::query()->updateOrCreate(
                ['slug' => $collection['slug']],
                $collection,
            );
        }
    }
}
