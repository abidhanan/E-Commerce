@extends('Users.Template.index')

@push('css')
    <style>
        .example-page {
            min-height: 60vh;
            padding: 120px 40px 80px;
            max-width: 960px;
            margin: 0 auto;
        }

        /* =========================
                                                                                                               BANNER GLOBAL
                                                                                                            ========================= */
        .banner {
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .banner img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .banner-content {
            position: absolute;
            left: 50px;
            bottom: 60px;
            z-index: 2;
            color: #fff;
            max-width: 600px;
        }

        .banner-title {
            font-size: clamp(32px, 5vw, 50px);
            font-weight: 6;
            line-height: 1.;
            margin-bottom: 12px;
        }

        .banner-subtitle {
            font-size: clamp(14px, 2vw, 10px);
            line-height: 1.5;
        }

        /* optional dark overlay */
        .banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            z-index: 1;
            pointer-events: none;
        }

        /* =========================
                                                                                                               MOBILE
                                                                                                            ========================= */
        @media (max-width: 768px) {
            .banner-content {
                left: 20px;
                right: 20px;
                bottom: 30px;
                max-width: unset;
            }

            .banner-title {
                font-size: 15px;
            }

            .banner-subtitle {
                font-size: 10px;
            }
        }

        /* =========================
                                                                                               CATEGORY & COLLECTION SHOWCASE
                                                                                            ========================= */
        .pns-categories-section {
            background: #f6f6f4;
            padding-bottom: 68px;
        }

        .pns-categories-tabs {
            background: #fff;
        }

        .category-pns-new-wrapper {
            position: relative;
            margin-top: 0;
        }

        .category-pns-new-nav {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 18px;
        }

        .category-pns-new-prev,
        .category-pns-new-next {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(215, 215, 210, 0);
            background: rgba(215, 215, 215, 0);
            color: rgb(0, 0, 0);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.25s ease;
        }

        .category-pns-new-prev:hover,
        .category-pns-new-next:hover {
            background: #000;
            color: #fff;
        }

        .category-pns-new-slider {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 2px 2px 12px;
        }

        .category-pns-new-slider::-webkit-scrollbar {
            display: none;
        }

        .category-pns-new-slider .pns-cat-item {
            flex: 0 0 292px;
            text-decoration: none;
            color: inherit;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .category-pns-new-slider .pns-cat-img-wrap {
            width: 100%;
            aspect-ratio: 4/5;
            overflow: hidden;
            border-radius: 8px;
            background: #e8e8e3;
            position: relative;
        }

        .category-pns-new-slider .pns-cat-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s ease !important;
        }

        .category-pns-new-slider .pns-cat-item:hover .pns-cat-img-wrap img {
            transform: scale(1.045);
        }

        .pns-cat-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 8px;
            padding: 18px;
            color: #fff;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.02) 20%, rgba(0, 0, 0, 0.74) 100%);
        }

        .pns-cat-count {
            width: max-content;
            max-width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.42);
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(0, 0, 0, 0.24);
            backdrop-filter: blur(8px);
        }

        .category-pns-new-slider .pns-cat-label {
            color: #fff;
            padding: 0;
            font-size: 24px;
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: 0;
        }

        .pns-cat-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 600;
        }

        .pns-cat-mini-products {
            display: flex;
            gap: 8px;
            min-height: 52px;
        }

        .pns-cat-mini-products span {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            overflow: hidden;
            background: #ededeb;
            border: 1px solid #e1e1dc;
            display: inline-flex;
        }

        .pns-cat-mini-products img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .collection-showcase-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .collection-showcase-card {
            display: grid;
            grid-template-columns: minmax(260px, 34%) 1fr;
            min-height: 320px;
            background: #fff;
            border: 1px solid #e5e5df;
            border-radius: 8px;
            overflow: hidden;
        }

        .collection-showcase-cover {
            position: relative;
            overflow: hidden;
            background: #111;
        }

        .collection-showcase-cover img {
            width: 100%;
            height: 100%;
            min-height: 320px;
            object-fit: cover;
            opacity: 0.9;
            transition: transform 0.55s ease !important;
        }

        .collection-showcase-card:hover .collection-showcase-cover img {
            transform: scale(1.04);
        }

        .collection-cover-copy {
            position: absolute;
            inset: auto 0 0 0;
            color: #fff;
            padding: 22px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.74));
        }

        .collection-cover-copy span {
            display: block;
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.74);
            margin-bottom: 8px;
        }

        .collection-cover-copy h3 {
            margin: 0;
            font-size: clamp(26px, 3vw, 42px);
            line-height: 1;
            font-weight: 800;
            letter-spacing: 0;
        }

        .collection-cover-copy p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 13px;
        }

        .collection-products-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            padding: 18px;
            align-content: stretch;
        }

        .collection-product-link {
            display: flex;
            min-width: 0;
            flex-direction: column;
            color: #111;
            text-decoration: none;
        }

        .collection-product-img {
            width: 100%;
            aspect-ratio: 3/4;
            border-radius: 8px;
            overflow: hidden;
            background: #efefec;
            margin-bottom: 10px;
        }

        .collection-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease !important;
        }

        .collection-product-link:hover .collection-product-img img {
            transform: scale(1.04);
        }

        .collection-product-name {
            font-size: 13px;
            line-height: 1.25;
            font-weight: 650;
            color: #111;
        }

        .collection-product-price {
            margin-top: 4px;
            font-size: 12px;
            color: #666;
        }

        .collection-empty-state {
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            background: #fff;
            border: 1px solid #e5e5df;
            border-radius: 8px;
        }

        body.dark-mode .pns-categories-section {
            background: #121212;
        }

        body.dark-mode .pns-categories-tabs,
        body.dark-mode .collection-showcase-card {
            background: #181818;
            border-color: rgba(255, 255, 255, 0.12);
        }

        body.dark-mode .collection-product-link,
        body.dark-mode .collection-product-name {
            color: #f4f4f4;
        }

        body.dark-mode .collection-product-price {
            color: rgba(255, 255, 255, 0.62);
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .category-pns-new-nav {
                display: none;
            }

            .category-pns-new-slider {
                gap: 14px;
            }

            .category-pns-new-slider .pns-cat-item {
                flex: 0 0 220px;
            }

            .category-pns-new-slider .pns-cat-label {
                font-size: 20px;
            }

            .pns-cat-overlay {
                padding: 14px;
            }

            .pns-cat-mini-products {
                min-height: 44px;
            }

            .pns-cat-mini-products span {
                width: 44px;
                height: 44px;
            }

            .collection-showcase-card {
                grid-template-columns: 1fr;
            }

            .collection-showcase-cover img {
                min-height: 240px;
            }

            .collection-products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                padding: 14px;
            }
        }
    </style>
@endpush

@section('content')
    <main>

        {{-- BANNER 1 --}}
        <section class="banner banner_1">
            <img src="{{ $displays?->image_1_path ? asset('storage/' . $displays->image_1_path) : '' }}" alt="Banner 1">

            <div class="banner-content">
                <h1 class="banner-title">
                    {{ $displays?->image_1_title }}
                </h1>

                <div class="banner-subtitle">
                    {{ $displays?->image_1_sub_title }}
                </div>
            </div>
        </section>


        {{-- COLLECTION SECTION --}}
        <div class="collection-header">
            <h2 class="collection-title">Best Seller</h2>
            <div class="collection-nav">
                <button type="button" class="collection-prev">←</button>
                <button type="button" class="collection-next">→</button>
            </div>
        </div>

        <div class="block-wrapper-outer">
            <section class="block-wrapper" data-carousel="best-seller">
                @foreach ($bestsellers as $prod)
                    @php
                        $primaryImage = collect($prod->images)->firstWhere('is_primary', 1);
                        $hoverImage = collect($prod->images)->firstWhere('is_hover', 1);
                    @endphp

                    <a href="{{ route('product.show', $prod->slug) }}" class="product-block">

                        @if (in_array($prod->id, $newArrivalIds))
                            <div class="product-badges">
                                <span class="new-arrival-badge">NEW ARRIVAL</span>
                            </div>
                        @endif
                        <div class="product-image-wrapper">
                            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage['image']) : 'https://via.placeholder.com/600x750' }}"
                                alt="{{ $prod->name }}" class="product-image img-main">

                            <img src="{{ $hoverImage ? asset('storage/' . $hoverImage['image']) : ($primaryImage ? asset('storage/' . $primaryImage['image']) : '') }}"
                                alt="{{ $prod->name }}" class="product-image img-hover">
                        </div>

                        <div class="product-info">
                            <div class="product-name">{{ $prod->name }}</div>
                            <div class="product-price">
                                Rp {{ number_format($prod->variants[0]['price'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                    </a>
                @endforeach
            </section>
        </div>


        {{-- BANNER 2 --}}
        <section class="banner banner_2">
            <img src="{{ $displays?->image_2_path ? asset('storage/' . $displays->image_2_path) : '' }}" alt="Banner 2">

            <div class="banner-content">
                <div class="banner-title">
                    {{ $displays?->image_2_title }}
                </div>
                <div class="banner-subtitle">
                    {{ $displays?->image_2_sub_title }}
                </div>
            </div>
        </section>



        <section class="pns-categories-section">
            <div class="pns-categories-tabs">
                <button class="pns-tab active" onclick="switchTab(this, 'categories')">Categories</button>
                <button class="pns-tab" onclick="switchTab(this, 'collections')">Collections</button>
            </div>

            <div id="tab-categories" class="pns-tab-content active">

                <div class="category-pns-new-wrapper">

                    <div class="category-pns-new-nav">
                        <button class="category-pns-new-prev" type="button" aria-label="Previous categories">
                            &larr;
                        </button>
                        <button class="category-pns-new-next" type="button" aria-label="Next categories">
                            &rarr;
                        </button>
                    </div>

                    <div class="category-pns-new-slider">
                        @forelse ($categories as $cat)
                            @php
                                $previewProducts = $cat->products->take(3);
                                $heroProduct = $previewProducts->first();
                                $heroImage = $heroProduct
                                    ? $heroProduct->images->firstWhere('is_primary', true) ??
                                        $heroProduct->images->first()
                                    : null;
                                $categoryImage = $heroImage
                                    ? asset('storage/' . $heroImage->image)
                                    : ($cat->img
                                        ? asset('storage/' . $cat->img)
                                        : 'https://via.placeholder.com/600x750');
                            @endphp

                            <a href="{{ route('category.show', $cat->slug) }}" class="pns-cat-item">
                                <div class="pns-cat-img-wrap">
                                    <img src="{{ asset('storage/' . $cat->img) }}" alt="{{ $cat->name }}">
                                    <div class="pns-cat-overlay">
                                        <span class="pns-cat-count">
                                            {{ number_format($cat->active_products_count, 0, ',', '.') }} products
                                        </span>
                                        <span class="pns-cat-label">{{ $cat->name }}</span>
                                        <span class="pns-cat-cta">
                                            Shop category <span aria-hidden="true">&#8599;</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="pns-cat-mini-products" aria-hidden="true">
                                    @foreach ($previewProducts as $previewProduct)
                                        @php
                                            $previewImage =
                                                $previewProduct->images->firstWhere('is_primary', true) ??
                                                $previewProduct->images->first();
                                        @endphp

                                        <span>
                                            <img src="{{ $previewImage ? asset('storage/' . $previewImage->image) : 'https://via.placeholder.com/200x260' }}"
                                                alt="">
                                        </span>
                                    @endforeach
                                </div>
                            </a>
                        @empty
                            <div class="collection-empty-state w-100">Belum ada kategori dengan produk aktif.</div>
                        @endforelse
                    </div>

                </div>
            </div>

            <div id="tab-collections" class="pns-tab-content">

                <div class="category-pns-new-wrapper">

                    <div class="category-pns-new-nav">
                        <button class="category-pns-new-prev" type="button" aria-label="Previous collections">
                            &larr;
                        </button>
                        <button class="category-pns-new-next" type="button" aria-label="Next collections">
                            &rarr;
                        </button>
                    </div>

                    <div class="category-pns-new-slider">
                        @forelse ($collections as $collect)
                            @php
                                $previewProducts = $collect->products->take(3);
                                $heroProduct = $previewProducts->first();
                                $heroImage = $heroProduct
                                    ? $heroProduct->images->firstWhere('is_primary', true) ??
                                        $heroProduct->images->first()
                                    : null;
                                $collectionImage = $heroImage
                                    ? asset('storage/' . $heroImage->image)
                                    : ($collect->img
                                        ? asset('storage/' . $collect->img)
                                        : 'https://via.placeholder.com/600x750');
                            @endphp

                            <a href="{{ route('collection.show', $collect->slug) }}" class="pns-cat-item">
                                <div class="pns-cat-img-wrap">
                                    <img src="{{ asset('storage/' . $collect->img) }}" alt="{{ $collect->name }}">
                                    <div class="pns-cat-overlay">
                                        <span class="pns-cat-count">
                                            {{ number_format($collect->active_products_count, 0, ',', '.') }} products
                                        </span>
                                        <span class="pns-cat-label">{{ $collect->name }}</span>
                                        <span class="pns-cat-cta">
                                            Open collection <span aria-hidden="true">&#8599;</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="pns-cat-mini-products" aria-hidden="true">
                                    @foreach ($previewProducts as $previewProduct)
                                        @php
                                            $previewImage =
                                                $previewProduct->images->firstWhere('is_primary', true) ??
                                                $previewProduct->images->first();
                                        @endphp

                                        <span>
                                            <img src="{{ $previewImage ? asset('storage/' . $previewImage->image) : 'https://via.placeholder.com/200x260' }}"
                                                alt="">
                                        </span>
                                    @endforeach
                                </div>
                            </a>
                        @empty
                            <div class="collection-empty-state w-100">Belum ada collection dengan produk aktif.</div>
                        @endforelse
                    </div>

                </div>
            </div>
        </section>


        {{-- BANNER 3 --}}
        <section class="banner banner_3">
            <img src="{{ $displays?->image_3_path ? asset('storage/' . $displays->image_3_path) : '' }}" alt="Banner 3">

            <div class="banner-content">
                <div class="banner-title">
                    {{ $displays?->image_3_title }}
                </div>
                <div class="banner-subtitle">
                    {{ $displays?->image_3_sub_title }}
                </div>
            </div>
        </section>

        <div class="collection-header">
            <h2 class="collection-title">{{ $customCollectionsname }} Collections</h2>
            <div class="collection-nav">
                <button type="button" class="collection-prev">←</button>
                <button type="button" class="collection-next">→</button>
            </div>
        </div>

        <div class="block-wrapper-outer">
            <section class="block-wrapper" data-carousel="best-seller">
                @foreach ($customCollections as $prod)
                    @php
                        $primaryImage = collect($prod['products']->images)->firstWhere('is_primary', 1);
                        $hoverImage = collect($prod['products']->images)->firstWhere('is_hover', 1);
                    @endphp

                    <a href="{{ route('product.show', $prod['products']->slug) }}" class="product-block">

                        @if (in_array($prod['products']->id, $newArrivalIds))
                            <div class="product-badges">
                                <span class="new-arrival-badge">NEW ARRIVAL</span>
                            </div>
                        @endif

                        <div class="product-image-wrapper">
                            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage['image']) : 'https://via.placeholder.com/600x750' }}"
                                alt="{{ $prod['products']->name }}" class="product-image img-main">

                            <img src="{{ $hoverImage ? asset('storage/' . $hoverImage['image']) : ($primaryImage ? asset('storage/' . $primaryImage['image']) : '') }}"
                                alt="{{ $prod['products']->name }}" class="product-image img-hover">
                        </div>

                        <div class="product-info">
                            <div class="product-name">{{ $prod['products']->name }}</div>
                            <div class="product-price">
                                Rp {{ number_format($prod['products']->variants[0]['price'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                    </a>
                @endforeach
            </section>
        </div>

        {{-- OUR STORY --}}
        <section class="our-story-section">
            <div class="our-story-header">
                <h2 class="our-story-title">Our Story</h2>
                <a href="{{ route('explore.index') }}" class="our-story-cta">Read More →</a>
            </div>

            <div class="our-story-grid">
                @foreach ($posts as $post)
                    <a href="{{ route('post.show', $post->slug) }}" class="our-story-card">
                        <div class="our-story-img-wrap">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}">
                        </div>

                        <div class="our-story-card-body">
                            <div class="our-story-card-tag">
                                {{ $post->category->name ?? '-' }}
                            </div>

                            <h3 class="our-story-card-title">
                                {{ $post->title }}
                            </h3>

                            <p class="our-story-card-desc">
                                {{ \Illuminate\Support\Str::limit($post->excerpt, 100) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>


        {{-- MARQUEE --}}
        <section class="pns-marquee-section">
            <div class="pns-marquee-track">
                <div class="pns-marquee-inner">
                    <span class="pns-marquee-item">
                        <span class="pns-marquee-text">
                            {{ $displays?->running_text }}
                        </span>
                    </span>
                </div>
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll(".category-pns-new-wrapper").forEach(function(wrapper) {
                const slider = wrapper.querySelector(".category-pns-new-slider");
                const prevBtn = wrapper.querySelector(".category-pns-new-prev");
                const nextBtn = wrapper.querySelector(".category-pns-new-next");

                if (!slider || !prevBtn || !nextBtn) {
                    return;
                }

                prevBtn.addEventListener("click", function() {
                    slider.scrollBy({
                        left: -400,
                        behavior: "smooth"
                    });
                });

                nextBtn.addEventListener("click", function() {
                    slider.scrollBy({
                        left: 400,
                        behavior: "smooth"
                    });
                });
            });
        });
    </script>
@endpush
