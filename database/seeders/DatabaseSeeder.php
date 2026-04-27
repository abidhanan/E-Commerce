<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            AccessControlSeeder::class,
            CategoryProductSeeder::class,
            CollectionSeeder::class,
            ProductSeeder::class,
            SellerSeeder::class,
            BlogCategorySeeder::class,
            TagBlogSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            SystemTableSeeder::class,
        ]);
    }
}
