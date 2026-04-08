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
                'price' => 120.00, 
                'image' => 'chanel-1.jpg', 
                'rating' => 5
            ],
            [
                'name' => 'Louis Vuitton', 
                'price' => 85.00, 
                'image' => '5.jpg', 
                'rating' => 4
            ],
            [
                'name' => 'Prada', 
                'price' => 150.00, 
                'image' => '3.jpg', 
                'rating' => 5
            ],
            [
                'name' => 'Versace', 
                'price' => 210.00, 
                'image' => '4.jpg', 
                'rating' => 5
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}