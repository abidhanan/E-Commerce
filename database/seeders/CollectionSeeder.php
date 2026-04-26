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
                'name' => 'Alpine Core',
                'slug' => 'alpine-core',
                'img' => 'collections/collection-placeholder.svg',
            ],
            [
                'name' => 'Trail Motion',
                'slug' => 'trail-motion',
                'img' => 'collections/collection-placeholder.svg',
            ],
            [
                'name' => 'City Commute',
                'slug' => 'city-commute',
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
