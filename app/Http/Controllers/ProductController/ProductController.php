<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CategoryProduct as Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Models\Collections;
use App\Models\Material;
use App\Models\Breathability;
use App\Models\Insulation;
use App\Models\TemperatureProduct as Temperature;
use App\Models\Intensities;
use App\Models\SizeGuide;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // PERBAIKAN: Ubah dari 2 menjadi 15 atau 20 untuk kenyamanan dashboard admin
        $products = $query->latest()->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('Admin.Products.partials.table', compact('products'))->render(),
                'pagination' => $products->links()->render()
            ]);
        }

        return view('Admin.Products.index', compact('products'));
    }
    
    public function create()
    {
        $sizeGuides = SizeGuide::all();
        $categories = Category::all();
        $collections = Collections::all();
        $materials = Material::all();
        return view('Admin.Products.create', compact('categories','collections','materials','sizeGuides'));
    }
         

    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'collection_id' => 'nullable|exists:collections,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:10000',
        'images' => 'nullable|array|max:8',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2080',
        'size_guide_id' => 'nullable|exists:size_guides,id',
        'temperature' => 'nullable|integer|min:-10|max:30',
        'intensity' => 'nullable|in:low,high',
        'insulation' => 'nullable|integer',
        'breathability' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
        'material' => 'nullable|array',
        'material.*' => 'integer|exists:materials,id',
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string|max:100',
        'variants.*.size' => 'required|string|max:20',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.stock' => 'required|integer|min:0',
    ]);

    try {
        DB::transaction(function () use ($request) {
            $product = Product::create([
                'category_id' => $request->category_id,
                'collection_id' => $request->collection_id,
                'size_guide_id' => $request->size_guide_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . time(),
                'description' => $request->description,
                'material' => $request->material ?? [],
                'gender' => $request->gender ?? 'unisex',
                'weight' => $request->weight ?? 0,
                'temperature' => $request->temperature ?? 0,
                'intensity' => $request->intensity ?? 'low',
                'insulation' => $request->insulation ?? 0,
                'breathability' => $request->breathability ?? 0,
                'is_active' => $request->is_active ?? true,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $key => $image) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $image->store('products', 'public'),
                        'is_primary' => $key === 0
                    ]);
                }
            }

            if ($request->variants) {
                foreach ($request->variants as $variant) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variant['sku'],
                        'color' => $variant['color'] ?? null,
                        'size' => $variant['size'] ?? null,
                        'price' => $variant['price'],
                        'stock' => $variant['stock'] ?? 0,
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index');

    } catch (Throwable $e) {
        report($e);
        return back()->withInput()->with('error', 'Produk gagal disimpan. Kesalahan fatal pada database.');
    }
}

    public function edit(Product $product)
    {
        $sizeGuides = SizeGuide::all();
        $categories = Category::all();
        $product->load(['images','variants']);
        $collections = Collections::all();
        $materials = Material::all();
        return view('Admin.Products.edit', compact('product','categories','collections','materials','sizeGuides'));
    }

  public function update(Request $request, Product $product)
{

    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'collection_id' => 'nullable|exists:collections,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:10000',
        'material' => 'nullable|array',
        'size_guide_id' => 'nullable|exists:size_guides,id',
        'images' => 'nullable|array|max:8',
        'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2080',
        'material.*' => 'integer|exists:materials,id',
        'temperature' => 'nullable|integer|min:-10|max:30',
        'intensity' => 'nullable|in:low,high',
        'insulation' => 'nullable|integer|min:0',
        'breathability' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string|max:100',
        'variants.*.size' => 'required|string|max:20',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.stock' => 'required|integer|min:0',
    ]);

    DB::transaction(function () use ($request, $product) {

        $product->update([
            'category_id' => $request->category_id,
            'collection_id' => $request->collection_id,
            'size_guide_id' => $request->size_guide_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'material' => $request->material ?? [],
            'gender' => $request->gender ?? 'unisex',
            'weight' => $request->weight ?? 0,

            'temperature' => $request->temperature ?? 0,
            'intensity' => $request->intensity ?? 'low',
            'insulation' => $request->insulation ?? 0,
            'breathability' => $request->breathability ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        // Tambah gambar baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => false
                ]);
            }
        }

        // Replace variants
        if ($request->variants) {

            $product->variants()->delete();

            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variant['sku'],
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'price' => $variant['price'],
                    'stock' => $variant['stock'] ?? 0,
                ]);
            }

        }

    });

    return redirect()->route('admin.products.index');
} 
public function setPrimary($id)
{
    $image = ProductImage::findOrFail($id);

    // reset semua primary
    ProductImage::where('product_id', $image->product_id)
        ->update(['is_primary' => 0]);

    // set yang dipilih jadi primary
    $image->update([
        'is_primary' => 1
    ]);

    return back()->with('success', 'Primary updated');
}

public function setHover($id)
{
    $image = ProductImage::findOrFail($id);

    // reset hover image
    ProductImage::where('product_id', $image->product_id)
        ->update(['is_hover' => 0]);

    // set hover baru
    $image->update([
        'is_hover' => 1
    ]);

    return back()->with('success', 'Hover image updated');
}

    public function destroy(Product $product)
    {
        // HAPUS logika Storage::disk('public')->delete(...) di sini.
        // Biarkan file fisik tetap ada jika kita hanya melakukan soft delete.
        
        $product->delete(); // Ini sekarang akan melakukan soft delete otomatis

        return back();
    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Image deleted');
    }
}
