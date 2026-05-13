@extends('Users.Template.index')

@section('title', $category->name . ' Category')

@push('css')
    <style>
        .listing-page {
            background: #f6f6f4;
            color: #111;
        }

        .listing-hero {
            padding: 40px 40px 40px;
            background: #fff;
            border-bottom: 1px solid #e5e5df;
        }

        .listing-eyebrow {
            display: block;
            margin-bottom: 12px;
            color: #777;
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .listing-title {
            margin: 0;
            font-size: clamp(42px, 7vw, 55px);
            line-height: 0.96;
            font-weight: 8;
            letter-spacing: 0;
        }

        .listing-subtitle {
            margin-top: 18px;
            max-width: 680px;
            color: #666;
            line-height: 1.6;
            font-size: 15px;
        }

        .listing-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            padding: 24px 40px;
            background: #f6f6f4;
        }

        .listing-toolbar a {
            font-size: 12px;
  font-weight: 500;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: #000;
  text-decoration: none;
  border-bottom: 1px solid #000;
  padding-bottom: 2px;
  white-space: nowrap;
  align-self: flex-end;
        }

        .listing-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            padding: 0 40px 48px;
        }

        .listing-product-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            color: #111;
            text-decoration: none;
        }

        .listing-product-image {
            position: relative;
            width: 100%;
            aspect-ratio: 3/4;
            overflow: hidden;
            border-radius: 8px;
            background: #ecece8;
        }

        .listing-product-image img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 0.35s ease, transform 0.55s ease !important;
        }

        .listing-product-hover {
            opacity: 0;
        }

        .listing-product-card:hover .listing-product-main {
            opacity: 0;
            transform: scale(1.035);
        }

        .listing-product-card:hover .listing-product-hover {
            opacity: 1;
            transform: scale(1.035);
        }

        .listing-product-copy {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 12px 2px 0;
        }

        .listing-product-name {
            font-size: 14px;
            line-height: 1.3;
            font-weight: 650;
        }

        .listing-product-price {
            color: #666;
            font-size: 13px;
        }

        .listing-empty {
            margin: 0 40px 64px;
            min-height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            background: #fff;
            border: 1px solid #e5e5df;
            border-radius: 8px;
        }

        .listing-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 14px;
            padding: 0 40px 64px;
        }

        .listing-pagination a,
        .listing-pagination span {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid #d8d8d2;
            background: #fff;
            padding: 0 16px;
            color: #111;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
        }

        .listing-pagination span {
            color: #666;
            font-weight: 600;
        }

        body.dark-mode .listing-page,
        body.dark-mode .listing-toolbar {
            background: #121212;
        }

        body.dark-mode .listing-hero,
        body.dark-mode .listing-pagination a,
        body.dark-mode .listing-pagination span,
        body.dark-mode .listing-empty {
            background: #181818;
            border-color: rgba(255, 255, 255, 0.12);
        }

        body.dark-mode .listing-product-card,
        body.dark-mode .listing-title {
            color: #f5f5f5;
        }
        body.dark-mode .listing-toolbar a {
            color: #f5f5f5;
            border-bottom: 1px solid #fff;
        }

        body.dark-mode .listing-subtitle,
        body.dark-mode .listing-product-price,
        body.dark-mode .listing-eyebrow,
        body.dark-mode .listing-pagination span {
            color: rgba(255, 255, 255, 0.64);
        }

        @media (max-width: 1024px) {
            .listing-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .listing-hero {
                padding: 112px 20px 32px;
            }

            .listing-toolbar {
                padding: 20px;
                align-items: flex-start;
                flex-direction: column;
            }

            .listing-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
                padding: 0 20px 40px;
            }

            .listing-empty {
                margin: 0 20px 44px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="listing-page">
        <section class="listing-hero">
            <span class="listing-eyebrow">Category</span>
            <h1 class="listing-title">{{ $category->name }}</h1>
            <p class="listing-subtitle">
                Discover curated pieces designed to elevate your everyday style.
                Crafted with quality materials, timeless details, and made to move with you.
            </p>
        </section>

        <div class="listing-toolbar">
            <div>{{ number_format($products->total(), 0, ',', '.') }} items available</div>
            <a href="{{ route('home') }}">Continue Shopping</a>
        </div>



        @if ($products->isNotEmpty())
            <section class="listing-grid">
                @foreach ($products as $product)
                    @include('Users.partials.product-card', ['product' => $product])
                @endforeach
            </section>

            @if ($products->hasPages())
                <nav class="listing-pagination" aria-label="Category pagination">
                    @if (!$products->onFirstPage())
                        <a href="{{ $products->previousPageUrl() }}">Previous</a>
                    @endif
                    <span>Page {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                    @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}">Next</a>
                    @endif
                </nav>
            @endif
        @else
            <div class="listing-empty">Belum ada produk aktif di kategori ini.</div>
        @endif
    </main>
@endsection
