<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Collections;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageShowcaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_links_to_separate_category_and_collection_pages(): void
    {
        [$category, $collection, $emptyCollection] = $this->createCatalogProduct();

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Jackets')
            ->assertSee('Alpine Core')
            ->assertSee(route('category.show', $category->slug), false)
            ->assertSee(route('collection.show', $collection->slug), false)
            ->assertDontSee($emptyCollection->name);
    }

    public function test_collection_page_shows_collection_products_with_pagination(): void
    {
        [, $collection] = $this->createCatalogProduct();

        $response = $this->get(route('collection.show', $collection->slug));

        $response
            ->assertOk()
            ->assertSee('Collection')
            ->assertSee('Alpine Core')
            ->assertSee('Alpine Shield Jacket')
            ->assertSee('Rp 749.000');
    }

    public function test_category_page_shows_category_products_with_pagination(): void
    {
        [$category] = $this->createCatalogProduct();

        $response = $this->get(route('category.show', $category->slug));

        $response
            ->assertOk()
            ->assertSee('Category')
            ->assertSee('Jackets')
            ->assertSee('Alpine Shield Jacket')
            ->assertSee('Rp 749.000');
    }

    private function createCatalogProduct(): array
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);

        $collection = Collections::create([
            'name' => 'Alpine Core',
            'slug' => 'alpine-core',
            'img' => 'collections/collection-placeholder.svg',
        ]);

        $emptyCollection = Collections::create([
            'name' => 'Empty Collection',
            'slug' => 'empty-collection',
            'img' => 'collections/collection-placeholder.svg',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'collection_id' => $collection->id,
            'name' => 'Alpine Shield Jacket',
            'slug' => 'alpine-shield-jacket',
            'description' => 'Shell jacket',
            'material' => ['Nylon'],
            'gender' => 'unisex',
            'weight' => 420,
            'temperature' => 4,
            'intensity' => 'high',
            'insulation' => 72,
            'breathability' => 88,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'ASH-JKT-M',
            'size' => 'M',
            'price' => 749000,
            'stock' => 10,
        ]);

        return [$category, $collection, $emptyCollection];
    }
}
