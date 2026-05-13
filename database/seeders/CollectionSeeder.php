<?php

namespace Database\Seeders;

use App\Models\Collections;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        $collections = [
            ['name' => 'Alpine Core', 'slug' => 'alpine-core', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Trail Motion', 'slug' => 'trail-motion', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'City Commute', 'slug' => 'city-commute', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Summit Line', 'slug' => 'summit-line', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Ridge Utility', 'slug' => 'ridge-utility', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Forest Layer', 'slug' => 'forest-layer', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Desert Air', 'slug' => 'desert-air', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Coastal Drizzle', 'slug' => 'coastal-drizzle', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Urban Trek', 'slug' => 'urban-trek', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Glacier Transit', 'slug' => 'glacier-transit', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Canyon Light', 'slug' => 'canyon-light', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Night Run', 'slug' => 'night-run', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Monsoon Ready', 'slug' => 'monsoon-ready', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Peak Shelter', 'slug' => 'peak-shelter', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Nomad Travel', 'slug' => 'nomad-travel', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Summit Shift', 'slug' => 'summit-shift', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Trail Form', 'slug' => 'trail-form', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Metro Shell', 'slug' => 'metro-shell', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Timber Camp', 'slug' => 'timber-camp', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Aurora Layer', 'slug' => 'aurora-layer', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Atlas Motion', 'slug' => 'atlas-motion', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Horizon Pack', 'slug' => 'horizon-pack', 'img' => 'collections/collection-placeholder.svg'],
            ['name' => 'Granite Works', 'slug' => 'granite-works', 'img' => 'collections/collection-placeholder.svg'],
        ];

        foreach ($collections as $collection) {
            Collections::query()->updateOrCreate(
                ['slug' => $collection['slug']],
                $collection,
            );
        }
    }
}
