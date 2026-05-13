<?php

namespace Tests\Feature;

use App\Models\Breathability;
use App\Models\CareGuide;
use App\Models\CategoryProduct;
use App\Models\Insulation;
use App\Models\Intensities;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SizeGuide;
use App\Models\TemperatureProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_show_orders_alpha_sizes_and_keeps_related_wishlist_outside_product_link(): void
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);

        $product = $this->createProduct($category, 'alpine-shield-jacket');
        foreach (['L', 'S', 'M'] as $size) {
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => 'ASH-'.$size,
                'size' => $size,
                'price' => 749000,
                'stock' => 5,
            ]);
        }

        $relatedProduct = $this->createProduct($category, 'summit-fleece-zip');
        ProductVariant::create([
            'product_id' => $relatedProduct->id,
            'sku' => 'SMF-M',
            'size' => 'M',
            'price' => 529000,
            'stock' => 3,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response
            ->assertOk()
            ->assertSeeInOrder(['<span>S</span>', '<span>M</span>', '<span>L</span>'], false)
            ->assertSee(route('product.show', $relatedProduct->slug), false)
            ->assertSee('Related Product');
    }

    public function test_product_show_disables_cart_actions_when_all_variants_are_unavailable(): void
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);
        $product = $this->createProduct($category, 'sold-out-jacket');

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SOJ-M',
            'size' => 'M',
            'price' => 300000,
            'stock' => 0,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response
            ->assertOk()
            ->assertSee('Unavailable')
            ->assertSee('disabled', false);
    }

    public function test_product_show_uses_reference_tables_for_product_attributes(): void
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);

        $sizeGuide = SizeGuide::create([
            'type' => 'outerwear',
            'name' => 'Outerwear Standard',
            'img' => 'size_guides/outerwear-standard.png',
            'data' => [
                'sizes' => [
                    [
                        'size' => 'M',
                        'measurements' => [
                            ['label' => 'Chest', 'type' => 'range', 'min' => 97, 'max' => 103, 'unit' => 'cm'],
                        ],
                    ],
                ],
            ],
        ]);

        $material = Material::create([
            'material' => 'Nylon Ripstop',
            'description' => '<div><strong>Ripstop</strong> weave for abrasion resistance.</div><script>alert("x")</script>',
            'image' => 'materials/nylon-ripstop.png',
        ]);

        TemperatureProduct::create([
            'min_temperature' => 1,
            'max_temperature' => 10,
            'label' => 'Cool Weather',
            'description' => 'Built for cool air and changing weather.',
        ]);

        Intensities::create([
            'label' => 'high',
            'description' => 'High output movement for steep climbs.',
        ]);

        Insulation::create([
            'level' => 5,
            'label' => '5/6',
            'description' => 'Reliable warmth for colder sessions.',
        ]);

        Breathability::create([
            'level' => 6,
            'label' => '6/6',
            'description' => 'Maximum airflow for long efforts.',
        ]);

        CareGuide::create([
            'question' => 'How should it be washed?',
            'answer' => "Wash cold only.\nDry in shade.",
            'position' => 1,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'size_guide_id' => $sizeGuide->id,
            'name' => 'Reference Driven Jacket',
            'slug' => 'reference-driven-jacket',
            'description' => 'Technical shell',
            'material' => [$material->id],
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
            'sku' => 'RDJ-M',
            'size' => 'M',
            'price' => 749000,
            'stock' => 5,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response
            ->assertOk()
            ->assertSee('Built for cool air and changing weather.')
            ->assertSee('High output movement for steep climbs.')
            ->assertSee('Reliable warmth for colder sessions.')
            ->assertSee('Maximum airflow for long efforts.')
            ->assertSee('<strong>Ripstop</strong>', false)
            ->assertDontSee('alert("x")', false)
            ->assertSee('How should it be washed?: Wash cold only. Dry in shade.')
            ->assertSee('storage/size_guides/outerwear-standard.png');
    }

    public function test_product_show_features_tab_lists_attached_materials_only(): void
    {
        $category = CategoryProduct::create([
            'name' => 'Jackets',
            'slug' => 'jackets',
            'img' => 'categories/category-placeholder.svg',
        ]);

        $primaryMaterial = Material::create([
            'material' => 'Nylon Ripstop',
            'description' => 'Ripstop weave for abrasion resistance.',
            'image' => 'materials/nylon-ripstop.png',
        ]);

        $secondaryMaterial = Material::create([
            'material' => 'Coated Polyester',
            'description' => 'Coated finish for weather protection.',
            'image' => 'materials/coated-polyester.png',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Material Focus Jacket',
            'slug' => 'material-focus-jacket',
            'description' => 'Technical shell',
            'material' => [$primaryMaterial->id, $secondaryMaterial->id],
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
            'sku' => 'MFJ-M',
            'size' => 'M',
            'price' => 749000,
            'stock' => 5,
        ]);

        $response = $this->get(route('product.show', $product->slug));

        $response
            ->assertOk()
            ->assertSee('>Nylon Ripstop<', false)
            ->assertSee('>Coated Polyester<', false)
            ->assertDontSee('>Overview<', false)
            ->assertDontSee('>Fit<', false)
            ->assertDontSee('>Care<', false);
    }

    private function createProduct(CategoryProduct $category, string $slug): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => str($slug)->replace('-', ' ')->headline()->toString(),
            'slug' => $slug,
            'description' => 'Product description',
            'material' => [],
            'gender' => 'unisex',
            'weight' => 420,
            'temperature' => 4,
            'intensity' => 'high',
            'insulation' => 72,
            'breathability' => 88,
            'is_active' => true,
        ]);
    }
}
