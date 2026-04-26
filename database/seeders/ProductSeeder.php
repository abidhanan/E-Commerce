<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\CategoryProduct;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil category berdasarkan slug
        $jackets = CategoryProduct::where('slug', 'jackets')->first();
        $fleece = CategoryProduct::where('slug', 'fleece')->first();
        $tops = CategoryProduct::where('slug', 'tops')->first();
        $pants = CategoryProduct::where('slug', 'pants')->first();

        $products = [
            [
                'name' => 'Chesterfield Coat',
                'price' => 499000,
                'image' => 'chanel-1.jpg',
                'rating' => 5,
                'category_id' => $jackets->id
            ],
            [
                'name' => 'Louis Vuitton',
                'price' => 850000,
                'image' => '5.jpg',
                'rating' => 4,
                'category_id' => $fleece->id
            ],
            [
                'name' => 'Prada',
                'price' => 150000,
                'image' => '3.jpg',
                'rating' => 5,
                'category_id' => $tops->id
            ],
            [
                'name' => 'Versace',
                'price' => 210000,
                'image' => '4.jpg',
                'rating' => 5,
                'category_id' => $pants->id
            ],
            [
                'name' => 'Dior',
                'price' => 300000,
                'image' => 'chanel-2.png',
                'rating' => 5,
                'category_id' => $jackets->id
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}