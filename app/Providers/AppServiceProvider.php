<?php

namespace App\Providers;

use App\Models\CategoryProduct;
use App\Models\Collections;
use App\Models\Product;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });

        View::composer('Users.Template.footer', function ($view) {
            if (!Schema::hasTable('social_links')) {
                $view->with('footerSocialLinks', collect());
                $view->with('footerMarketplaceLinks', collect());

                return;
            }

            $links = SocialLink::query()
                ->active()
                ->ordered()
                ->get();

            $view->with('footerSocialLinks', $links->where('type', SocialLink::TYPE_SOCIAL)->values());
            $view->with('footerMarketplaceLinks', $links->where('type', SocialLink::TYPE_MARKETPLACE)->values());
        });

        View::composer('Users.Template.shop-sidebar', function ($view) {
            if (!Schema::hasTable('categories') || !Schema::hasTable('collections') || !Schema::hasTable('products')) {
                $view->with('shopSidebarCategories', collect());
                $view->with('shopSidebarCollections', collect());
                $view->with('shopSidebarLatestProduct', null);

                return;
            }

            $categories = CategoryProduct::query()
                ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
                ->whereHas('products', fn ($query) => $query->where('is_active', true))
                ->orderByDesc('active_products_count')
                ->orderBy('name')
                ->get();

            $collections = Collections::query()
                ->withCount(['products as active_products_count' => fn ($query) => $query->where('is_active', true)])
                ->whereHas('products', fn ($query) => $query->where('is_active', true))
                ->orderByDesc('active_products_count')
                ->orderBy('name')
                ->get();

            $latestProduct = Product::query()
                ->with(['images'])
                ->where('is_active', true)
                ->latest()
                ->first();

            $view->with('shopSidebarCategories', $categories);
            $view->with('shopSidebarCollections', $collections);
            $view->with('shopSidebarLatestProduct', $latestProduct);
        });
    }
}
