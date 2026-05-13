@php
    $sidebarCategories = $shopSidebarCategories ?? collect();
    $sidebarCollections = $shopSidebarCollections ?? collect();
    $latestProduct = $shopSidebarLatestProduct ?? null;

    $latestProductImage = $latestProduct
        ? $latestProduct->images->firstWhere('is_primary', true) ?? $latestProduct->images->first()
        : null;

    $latestProductImageUrl = $latestProductImage
        ? asset('storage/' . $latestProductImage->image)
        : 'https://via.placeholder.com/900x1100?text=New+Arrival';
@endphp

<div class="shop-overlay" id="shopOverlay"></div>

<aside class="shop-sidebar" id="shopSidebar" aria-hidden="true" aria-label="Shop menu">
    <div class="shop-sidebar-header">
        <span>Menu</span>
        <button class="shop-sidebar-close" id="shopClose" type="button" aria-label="Close menu">
            &#x2715;
        </button>
    </div>

    @if ($latestProduct)
        <a href="{{ route('product.show', $latestProduct->slug ?: $latestProduct->id) }}" class="shop-sidebar-banner"
            onclick="closeShopSidebar()">
            <img src="{{ $latestProductImageUrl }}" alt="{{ $latestProduct->name }}">
            <div class="shop-sidebar-banner-label">
                {{ $latestProduct->name }}
            </div>
        </a>
    @else
        <div class="shop-sidebar-banner">
            <img src="{{ $latestProductImageUrl }}" alt="New Arrivals">
            <div class="shop-sidebar-banner-label">
                New Arrivals
            </div>
        </div>
    @endif

    <div class="shop-sidebar-body">

        <div class="shop-mobile-links" aria-label="Mobile menu">
            @if (Auth::check())
                <a href="{{ route('account.index') }}" class="shop-mobile-link" onclick="closeShopSidebar()">
                    Account
                </a>
            @else
                <a href="{{ route('login') }}" class="shop-mobile-link" onclick="closeShopSidebar()">
                    Login
                </a>
            @endif

            <a href="{{ route('explore.index') }}" class="shop-mobile-link" onclick="closeShopSidebar()">
                Explore
            </a>

            <a href="{{ route('about') }}" class="shop-mobile-link" onclick="closeShopSidebar()">
                About
            </a>

            <a href="{{ route('user.orders.index') }}" class="shop-mobile-link" onclick="closeShopSidebar()">
                Orders
            </a>
        </div>


        {{-- CATEGORY --}}
        <div class="shop-section-title">Category</div>

        <div class="shop-category-grid" id="categoryGrid">
            @forelse ($sidebarCategories as $index => $category)
                @php
                    $categoryImage = $category->img
                        ? asset('storage/' . $category->img)
                        : 'https://via.placeholder.com/80?text=' . urlencode(substr($category->name, 0, 1));
                @endphp

                <a href="{{ route('category.show', $category->slug) }}"
                    class="shop-category-item {{ $index >= 5 ? 'hidden-category' : '' }}" onclick="closeShopSidebar()">
                    <img src="{{ $categoryImage }}" class="shop-category-icon" alt="{{ $category->name }}">
                    <span>{{ $category->name }}</span>
                </a>
            @empty
                <div class="shop-sidebar-empty">
                    Belum ada kategori produk aktif.
                </div>
            @endforelse
        </div>

        @if ($sidebarCategories->count() > 5)
            <button type="button" class="shop-load-more-btn" id="loadMoreCategory">
                Load More Categories
            </button>
        @endif


        <div class="shop-divider"></div>


        {{-- COLLECTION --}}
        <div class="shop-section-title">Collection</div>

        <div class="shop-category-grid" id="collectionGrid">
            @forelse ($sidebarCollections as $index => $collection)
                @php
                    $collectionImage = $collection->img
                        ? asset('storage/' . $collection->img)
                        : 'https://via.placeholder.com/80?text=' . urlencode(substr($collection->name, 0, 1));
                @endphp

                <a href="{{ route('collection.show', $collection->slug) }}"
                    class="shop-category-item {{ $index >= 5 ? 'hidden-collection' : '' }}"
                    onclick="closeShopSidebar()">
                    <img src="{{ $collectionImage }}" class="shop-category-icon" alt="{{ $collection->name }}">
                    <span>{{ $collection->name }}</span>
                </a>
            @empty
                <div class="shop-sidebar-empty">
                    Belum ada collection produk aktif.
                </div>
            @endforelse
        </div>

        @if ($sidebarCollections->count() > 5)
            <button type="button" class="shop-load-more-btn" id="loadMoreCollection">
                Load More Collections
            </button>
        @endif

    </div>
</aside>
