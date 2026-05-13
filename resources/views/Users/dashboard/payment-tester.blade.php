@extends('Users.Template.index')

@section('title', 'Payment Tester')

@push('css')
    <style>
        .payment-tester-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #ffffff;
        }

        .payment-tester-shell {
            width: min(1100px, 100%);
            margin: 0 auto;
        }

        .payment-tester-header {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: end;
            padding-bottom: 18px;
            border-bottom: 1px solid #e6e6e6;
        }

        .payment-tester-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 400;
        }

        .payment-tester-header p {
            margin: 8px 0 0;
            color: #666666;
            font-size: 14px;
        }

        .payment-tester-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 32px;
            margin-top: 28px;
        }

        .payment-product-list,
        .payment-order-list {
            display: grid;
            gap: 14px;
        }

        .payment-product,
        .payment-order,
        .payment-empty {
            padding: 16px;
            border: 1px solid #e4e4e4;
        }

        .payment-product {
            display: grid;
            grid-template-columns: 96px 1fr auto;
            gap: 16px;
            align-items: center;
        }

        .payment-product img {
            width: 96px;
            aspect-ratio: 1;
            object-fit: cover;
            background: #f2f2f2;
        }

        .payment-product h2,
        .payment-order strong {
            margin: 0;
            font-size: 15px;
        }

        .payment-product p,
        .payment-order p,
        .payment-empty {
            margin: 6px 0 0;
            color: #666666;
            font-size: 13px;
            line-height: 1.6;
        }

        .payment-button {
            border: 0;
            background: #111111;
            color: #ffffff;
            cursor: pointer;
            padding: 12px 16px;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        body.dark-mode .payment-tester-page {
            background: #111111;
        }

        body.dark-mode .payment-tester-header,
        body.dark-mode .payment-product,
        body.dark-mode .payment-order,
        body.dark-mode .payment-empty {
            border-color: #303030;
        }

        body.dark-mode .payment-tester-header p,
        body.dark-mode .payment-product p,
        body.dark-mode .payment-order p,
        body.dark-mode .payment-empty {
            color: #bbbbbb;
        }

        body.dark-mode .payment-button {
            background: #ffffff;
            color: #111111;
        }

        @media (max-width: 860px) {
            .payment-tester-page {
                padding: 96px 16px 56px;
            }

            .payment-tester-grid,
            .payment-product {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <section class="payment-tester-page">
        <div class="payment-tester-shell">
            <div class="payment-tester-header">
                <div>
                    <h1>Payment Tester</h1>
                    <p>Dashboard beli sederhana untuk tester pembayaran.</p>
                </div>
                <a href="{{ route('checkout.index') }}" class="payment-button">Checkout Cart</a>
            </div>

            <div class="payment-tester-grid">
                <div>
                    <div class="payment-product-list">
                        @forelse ($products as $product)
                            @php
                                $variant = $product->variants->first(fn ($item) => (int) $item->stock > 0 && filled($item->size))
                                    ?? $product->variants->first();
                                $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                            @endphp

                            <div class="payment-product">
                                <img src="{{ $image ? asset('storage/' . $image->image) : 'https://via.placeholder.com/180' }}"
                                    alt="{{ $product->name }}">
                                <div>
                                    <h2>{{ $product->name }}</h2>
                                    <p>
                                        {{ $variant?->size ? 'Size ' . $variant->size : 'Belum ada size' }}
                                        / Rp {{ number_format($variant?->price ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                                @if ($variant)
                                    <a href="{{ route('checkout.show', $variant->id) }}" class="payment-button">
                                        Preview
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="payment-empty">Belum ada produk aktif</div>
                        @endforelse
                    </div>
                </div>

                <aside>
                    <div class="payment-order-list">
                        @forelse ($orders as $order)
                            <div class="payment-order">
                                <strong>{{ $order->order_code }}</strong>
                                <p>
                                    {{ str_replace('_', ' ', $order->status) }} /
                                    Rp {{ number_format($order->gross_amount, 0, ',', '.') }}
                                </p>
                                <a href="{{ route('payments.status', $order->order_code) }}" class="payment-button">
                                    Status
                                </a>
                            </div>
                        @empty
                            <div class="payment-empty">Belum ada order tester.</div>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
