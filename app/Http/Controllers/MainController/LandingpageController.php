<?php

namespace App\Http\Controllers\MainController;

use App\Http\Controllers\Controller;
use App\Models\Aboutus;
use App\Models\BestSellers;
use App\Models\CategoryProduct;
use App\Models\Collections;
use App\Models\Display;
use App\Models\Faq;
use App\Models\Material;
use App\Models\Post;
use App\Models\Product;
use App\Models\Breathability;
use App\Models\Insulation;
use App\Models\Intensities;
use App\Models\TemperatureProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProgressStep;
use App\Models\CareGuide;
use App\Models\CrashReplacement;
use App\Models\CustomCollectionsDisplay as CustomCollection;

class LandingpageController extends Controller
{
    public function index()
    {
    $categories = CategoryProduct::query()
            ->with(['products' => fn ($query) => $query
                ->where('is_active', true)
                ->with(['images', 'variants'])
                ->latest()
                ->limit(5)])
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('active_products_count')
            ->orderBy('name')
            ->get();

    $collections = Collections::query()
            ->with(['products' => fn ($query) => $query
                ->where('is_active', true)
                ->with(['images', 'variants'])
                ->latest()
                ->limit(3)])
            ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('active_products_count')
            ->orderBy('name')
            ->get();

       $newArrivalIds = Product::query()
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(7)
            ->pluck('id')
            ->toArray();

        $customCollections = CustomCollection::query()
            ->with('collection')
            ->with([
                'products' => fn ($query) => $query
                    ->where('is_active', true)
                    ->with(['images', 'variants'])
                    ->latest()
                    ->limit(3)
            ])
            ->withCount([
                'products as active_products_count' => fn ($query) => $query
                    ->where('is_active', true)
            ])
            ->whereHas('products', fn ($query) => $query->where('is_active', true))
            ->orderByDesc('active_products_count')
            ->orderBy('id')
            ->get();
        $customCollectionsname = $customCollections->pluck('collection.name')->first() ?? 'Custom Collection';
        $displays = Display::latest()->first();
        $bestsellers = BestSellers::with('product.images', 'product.variants')
            ->orderBy('position')
            ->get()
            ->pluck('product');
        $posts = Post::with(['category'])
        ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('Users.dashboard.index', compact(
            'categories',
            'collections',
            'displays',
            'posts',
            'customCollections',
            'customCollectionsname',
            'bestsellers',
            'newArrivalIds',
        ));
    }
    
    public function about()
    {
        $about = Aboutus::query()->first();

        return view('Users.about.index', compact('about'));
    }

    public function faq()
    {
        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        return view('Users.faq.index', compact('faqs'));
    }

    public function crashReplacement()
    {
      $crashReplacements = CrashReplacement::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        return view('Users.crash-replacement.index', compact('crashReplacements'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));
        $products = $this->searchProducts($keyword)
            ->limit($request->expectsJson() || $request->boolean('ajax') ? 6 : 24)
            ->get();

        if ($request->expectsJson() || $request->boolean('ajax')) {
            return response()->json([
                'products' => $products->map(fn(Product $product) => $this->formatSearchProduct($product))->values(),
            ]);
        }

        return view('Users.search.index', compact('keyword', 'products'));
    }

    public function product(string $product)
    {
        $product = Product::query()
            ->with(['category', 'collection', 'images', 'variants', 'sizeGuide'])
            ->where('is_active', true)
            ->where(function ($query) use ($product) {
                $query->where('slug', $product);

                if (ctype_digit($product)) {
                    $query->orWhereKey((int) $product);
                }
            })
            ->firstOrFail();

        $materials = Material::query()
            ->whereIn('id', collect($product->material)->filter()->values())
            ->get();

        $temperatureValue = (int) ($product->temperature ?? 0);
        $insulationPercent = max(0, min(100, (int) ($product->insulation ?? 0)));
        $breathabilityPercent = max(0, min(100, (int) ($product->breathability ?? 0)));
        $insulationLevel = (int) ceil(($insulationPercent / 100) * 6);
        $breathabilityLevel = (int) ceil(($breathabilityPercent / 100) * 6);

        $temperatureReference = TemperatureProduct::query()
            ->where('min_temperature', '<=', $temperatureValue)
            ->where(function ($query) use ($temperatureValue) {
                $query->where('max_temperature', '>=', $temperatureValue)
                    ->orWhereNull('max_temperature');
            })
            ->orderBy('min_temperature')
            ->first();

        $intensityReference = Intensities::query()
            ->where('label', $product->intensity ?: 'low')
            ->first();

        $insulationReference = Insulation::query()
            ->where('level', $insulationLevel)
            ->first();

        $breathabilityReference = Breathability::query()
            ->where('level', $breathabilityLevel)
            ->first();

        $careGuides = CareGuide::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        $newArrivalIds = Product::query()
            ->where('is_active', true)
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()
            ->take(7)
            ->pluck('id')
            ->toArray();
        $relatedProducts = Product::query()
            ->with(['images', 'variants'])
            ->where('is_active', true)
            ->whereKeyNot($product->id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id);

                if ($product->collection_id) {
                    $query->orWhere('collection_id', $product->collection_id);
                }
            })
            ->limit(4)
            ->get();

        return view('Users.product.show', compact(
            'product',
            'relatedProducts',
            'materials',
            'newArrivalIds',
            'temperatureReference',
            'intensityReference',
            'insulationReference',
            'breathabilityReference',
            'careGuides',
        ));
    }

    private function searchProducts(string $keyword)
    {
        return Product::query()
            ->with(['images', 'variants'])
            ->where('is_active', true)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhere('gender', 'like', "%{$keyword}%")
                        ->orWhereHas('category', fn($category) => $category->where('name', 'like', "%{$keyword}%"))
                        ->orWhereHas('collection', fn($collection) => $collection->where('name', 'like', "%{$keyword}%"));
                });
            })
            ->orderBy('name');
    }

    private function formatSearchProduct(Product $product): array
    {
        $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $price = $product->variants->sortBy('price')->first()?->price ?? 0;

        return [
            'name' => $product->name,
            'price' => 'Rp ' . number_format($price, 0, ',', '.'),
            'image' => $primaryImage ? asset('storage/' . $primaryImage->image) : 'https://via.placeholder.com/600x750',
            'url' => route('product.show', $product->slug),
            'variant' => Str::headline($product->gender ?: 'Available now'),
        ];
    }

    public function returnPolicy()
    {
        $steps = ProgressStep::where('module', 'return_module')
        ->where('is_active', true)
        ->orderBy('step_order')
        ->get();

    $currentStep = 2; // contoh current active step (dynamic dari order/user nanti)

    return view('users.return-policy.index', compact('steps', 'currentStep'));
    }
    public function howToBuy()
    {
        $steps = ProgressStep::where('module', 'how_to_buy_module')
        ->where('is_active', true)
        ->orderBy('step_order')
        ->get();
    $currentStep = 1; // contoh current active step (dynamic dari order/user nanti)
    return view('users.how-to-buy.index', compact('steps', 'currentStep'));
    }

     public function careGuide()
    {
        $guides = CareGuide::query()
            ->where('is_active', true)
            ->orderBy('position')
            ->get();

        return view('Users.care-guide.index', compact('guides'));
    }

}
