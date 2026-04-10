<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product; // Pastikan ini di-import

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Chesterfield Coat', 
                'price' => 499000, 
                'image' => 'chanel-1.jpg', 
                'rating' => 5
            ],
            [
                'name' => 'Louis Vuitton', 
                'price' => 850000, 
                'image' => '5.jpg', 
                'rating' => 4
            ],
            [
                'name' => 'Prada', 
                'price' => 150000, 
                'image' => '3.jpg', 
                'rating' => 5
            ],
            [
                'name' => 'Versace', 
                'price' => 210000, 
                'image' => '4.jpg', 
                'rating' => 5
            ],
            [
                'name' => 'Dior', 
                'price' => 300000, 
                'image' => 'chanel-2.png', 
                'rating' => 5
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}