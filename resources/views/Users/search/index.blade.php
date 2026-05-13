@extends('Users.Template.index')

@section('title', $keyword ? 'Search: ' . $keyword : 'Search Products')

@push('css')
    <style>
        .search-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #fff;
        }

        .search-shell {
            width: min(1280px, 100%);
            margin: 0 auto;
        }

        .search-page-header {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e6e6e6;
        }

        .search-page-title {
            font-size: 34px;
            font-weight: 400;
            line-height: 1.15;
        }

        .search-page-meta {
            color: #707070;
            font-size: 13px;
        }

        .search-page-form {
            display: flex;
            gap: 10px;
            margin-bottom: 28px;
        }

        .search-page-input {
            width: 100%;
            min-height: 46px;
            border: 1px solid #d9d9d9;
            background: #fff;
            padding: 0 14px;
            font: inherit;
            font-size: 14px;
        }

        .search-page-submit {
            min-height: 46px;
            border: 0;
            background: #111;
            color: #fff;
            padding: 0 22px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .search-results-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 22px 14px;
        }

        .search-empty {
            display: grid;
            place-items: center;
            min-height: 320px;
            padding: 32px;
            text-align: center;
            border: 1px solid #e6e6e6;
            background: #fafafa;
        }

        .search-empty-title {
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 400;
        }

        .search-empty-text {
            color: #777;
            font-size: 14px;
        }

        body.dark-mode .search-page {
            background: #111;
        }

        body.dark-mode .search-page-header,
        body.dark-mode .search-page-input,
        body.dark-mode .search-empty {
            border-color: #303030;
        }

        body.dark-mode .search-page-input,
        body.dark-mode .search-empty {
            background: #171717;
            color: #f1f1f1;
        }

        body.dark-mode .search-page-meta,
        body.dark-mode .search-empty-text {
            color: #bbb;
        }

        body.dark-mode .search-page-submit {
            background: #fff;
            color: #111;
        }

        @media (max-width: 1100px) {
            .search-results-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .search-page {
                padding: 96px 16px 56px;
            }

            .search-page-header {
                align-items: start;
                flex-direction: column;
            }

            .search-page-title {
                font-size: 28px;
            }

            .search-page-form {
                flex-direction: column;
            }

            .search-results-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

@section('content')
    <section class="search-page">
        <div class="search-shell">
            <div class="search-page-header">
                <h1 class="search-page-title">Search Products</h1>
                <div class="search-page-meta">
                    @if ($keyword)
                        {{ $products->count() }} result{{ $products->count() === 1 ? '' : 's' }} for "{{ $keyword }}"
                    @else
                        Type a product name, category, or collection.
                    @endif
                </div>
            </div>

            <form action="{{ route('search.index') }}" method="GET" class="search-page-form">
                <input type="search" name="q" value="{{ $keyword }}" class="search-page-input"
                    placeholder="Search products..." autocomplete="off">
                <button type="submit" class="search-page-submit">Search</button>
            </form>

            @if ($keyword === '')
                <div class="search-empty">
                    <div>
                        <h2 class="search-empty-title">Start your search</h2>
                        <p class="search-empty-text">Find products by name, category, collection, or description.</p>
                    </div>
                </div>
            @elseif ($products->isEmpty())
                <div class="search-empty">
                    <div>
                        <h2 class="search-empty-title">No products found</h2>
                        <p class="search-empty-text">Try another keyword or browse the latest products.</p>
                    </div>
                </div>
            @else
                <div class="search-results-grid">
                    @foreach ($products as $prod)
                        @php
                            $primaryImage = collect($prod->images)->firstWhere('is_primary', 1);
                            $hoverImage = collect($prod->images)->firstWhere('is_hover', 1);
                        @endphp

                        <a href="{{ route('product.show', $prod->slug) }}" class="product-block">
                            <div class="product-badges">
                                <button type="button" class="wishlist-btn" data-product-id="{{ $prod->id }}"
                                    aria-label="Add to wishlist" aria-pressed="false">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>

                            <div class="product-image-wrapper">
                                <img src="{{ $primaryImage ? asset('storage/' . $primaryImage['image']) : 'https://via.placeholder.com/600x750' }}"
                                    alt="{{ $prod->name }}" class="product-image img-main">
                                <img src="{{ $hoverImage ? asset('storage/' . $hoverImage['image']) : ($primaryImage ? asset('storage/' . $primaryImage['image']) : 'https://via.placeholder.com/600x750?text=Hover') }}"
                                    alt="{{ $prod->name }}" class="product-image img-hover">

                                <div class="size-selector" onclick="event.preventDefault()">
                                    @foreach ($prod->variants as $variant)
                                        <div class="size-option" onclick="event.stopPropagation()">
                                            {{ $variant['size'] }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="product-info">
                                <div class="product-name">{{ $prod->name }}</div>
                                <div class="product-price">
                                    Rp {{ number_format($prod->variants[0]['price'] ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
