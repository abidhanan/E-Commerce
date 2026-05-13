@extends('Users.Template.index')

@section('title', 'Wishlist')

@push('css')
    <style>
        .wishlist-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #fff;
        }

        .wishlist-shell {
            width: min(1280px, 100%);
            margin: 0 auto;
        }

        .wishlist-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e6e6e6;
        }

        .wishlist-title {
            font-size: 34px;
            font-weight: 400;
            line-height: 1.15;
        }

        .wishlist-count {
            color: #707070;
            font-size: 13px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 22px 14px;
        }

        .wishlist-empty {
            display: grid;
            place-items: center;
            min-height: 360px;
            padding: 32px;
            text-align: center;
            border: 1px solid #e6e6e6;
            background: #fafafa;
        }

        .wishlist-empty-title {
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 400;
        }

        .wishlist-empty-text {
            margin-bottom: 22px;
            color: #777;
            font-size: 14px;
        }

        .wishlist-empty-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 0 22px;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        body.dark-mode .wishlist-page {
            background: #111;
        }

        body.dark-mode .wishlist-heading,
        body.dark-mode .wishlist-empty {
            border-color: #303030;
        }

        body.dark-mode .wishlist-count,
        body.dark-mode .wishlist-empty-text {
            color: #bbb;
        }

        body.dark-mode .wishlist-empty {
            background: #171717;
        }

        body.dark-mode .wishlist-empty-link {
            background: #fff;
            color: #111;
        }

        @media (max-width: 1100px) {
            .wishlist-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .wishlist-page {
                padding: 96px 16px 56px;
            }

            .wishlist-heading {
                align-items: start;
                flex-direction: column;
            }

            .wishlist-title {
                font-size: 28px;
            }

            .wishlist-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>
@endpush

@section('content')
    <section class="wishlist-page">
        <div class="wishlist-shell">
            <div class="wishlist-heading">
                <h1 class="wishlist-title">Wishlist</h1>
                <div class="wishlist-count" id="wishlistPageCount">
                    {{ $products->count() }} product{{ $products->count() === 1 ? '' : 's' }}
                </div>
            </div>

            @if ($products->isEmpty())
                <div class="wishlist-empty">
                    <div>
                        <h2 class="wishlist-empty-title">Your wishlist is empty</h2>
                        <p class="wishlist-empty-text">Save products you like and find them here later.</p>
                        <a href="{{ route('home') }}" class="wishlist-empty-link">Start Shopping</a>
                    </div>
                </div>
            @else
                <div class="wishlist-grid" id="wishlistGrid">
                    @foreach ($products as $prod)
                        @php
                            $primaryImage = collect($prod->images)->firstWhere('is_primary', 1);
                            $hoverImage = collect($prod->images)->firstWhere('is_hover', 1);
                        @endphp

                        <a href="{{ route('product.show', $prod->slug) }}" class="product-block" data-wishlist-card="{{ $prod->id }}">
                            <div class="product-badges">
                                <button type="button" class="wishlist-btn active" data-product-id="{{ $prod->id }}"
                                    aria-label="Remove from wishlist" aria-pressed="true">
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
