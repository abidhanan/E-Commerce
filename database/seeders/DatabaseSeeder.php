<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategoryProductSeeder::class,
            CollectionSeeder::class,
            AboutUsSeeder::class,
            FaqSeeder::class,
            ProgressStepSeeder::class,
            CareGuideSeeder::class,
            CrashReplacementSeeder::class,
            SocialLinkSeeder::class,
            ProductAttributeSeeder::class,
            SizeGuideSeeder::class,
            ProductSeeder::class,
            BlogSeeder::class,
            CustomerOrderSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
