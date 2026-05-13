@extends('Users.Template.index')

@section('title', 'Riwayat Pembelian')

@php
    $statusLabels = [
        'waiting_admin' => 'Menunggu Admin',
        'quoted' => 'Menunggu Pembayaran',
        'pending' => 'Menunggu Pembayaran',
        'challenge' => 'Review Pembayaran',
        'paid' => 'Pembayaran Diterima',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'failed' => 'Gagal',
        'refunded' => 'Refund',
    ];

    $statusMessages = [
        'waiting_admin' => 'Pesanan sudah masuk. Admin sedang mengecek alamat, ongkir, dan total akhir.',
        'quoted' => 'Admin sudah memberi total akhir. Lanjutkan pembayaran lewat link Midtrans.',
        'pending' => 'Transaksi pembayaran sudah dibuat dan masih menunggu penyelesaian.',
        'challenge' => 'Pembayaran sedang direview oleh Midtrans atau bank.',
        'paid' => 'Pembayaran diterima. Pesanan akan masuk proses packing.',
        'processing' => 'Pesanan sedang diproses dan disiapkan.',
        'shipped' => 'Pesanan sudah dikirim.',
        'completed' => 'Pesanan sudah selesai.',
        'cancelled' => 'Pesanan dibatalkan.',
        'failed' => 'Pembayaran gagal atau transaksi kedaluwarsa.',
        'refunded' => 'Pembayaran sudah dikembalikan.',
    ];

    $stepLabels = ['Order', 'Admin', 'Bayar', 'Proses', 'Kirim', 'Selesai'];
    $stepForStatus = [
        'waiting_admin' => 2,
        'quoted' => 3,
        'pending' => 3,
        'challenge' => 3,
        'paid' => 4,
        'processing' => 5,
        'shipped' => 6,
        'completed' => 6,
        'cancelled' => 1,
        'failed' => 3,
        'refunded' => 3,
    ];
@endphp

@push('css')
    <style>
        .orders-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #ffffff;
        }

        .orders-shell {
            width: min(1120px, 100%);
            margin: 0 auto;
        }

        .orders-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e6e6e6;
        }

        .orders-title {
            margin: 0;
            font-size: 34px;
            font-weight: 400;
            line-height: 1.15;
        }

        .orders-count {
            color: #707070;
            font-size: 13px;
        }

        .orders-list {
            display: grid;
            gap: 18px;
            margin-top: 28px;
        }

        .order-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 28px;
            padding: 22px;
            border: 1px solid #dedede;
            color: inherit;
            text-decoration: none;
            transition: border-color 0.18s ease, transform 0.18s ease;
        }

        .order-card:hover {
            border-color: #111111;
            transform: translateY(-1px);
        }

        .order-card-head {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .order-code {
            margin: 0 0 5px;
            font-size: 18px;
            font-weight: 600;
        }

        .order-date,
        .order-message,
        .order-item-meta {
            color: #707070;
            font-size: 13px;
            line-height: 1.6;
        }

        .order-badge {
            display: inline-flex;
            min-height: 30px;
            align-items: center;
            padding: 0 10px;
            background: #111111;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .order-badge.warning {
            background: #f7e7bf;
            color: #6c4b00;
        }

        .order-badge.danger {
            background: #f4c8c8;
            color: #8f1717;
        }

        .order-badge.success {
            background: #dcefdc;
            color: #166534;
        }

        .order-items {
            display: grid;
            gap: 10px;
        }

        .order-item {
            display: grid;
            grid-template-columns: 58px 1fr;
            gap: 12px;
            align-items: center;
        }

        .order-item-image {
            width: 58px;
            aspect-ratio: 1;
            object-fit: cover;
            background: #f0f0f0;
        }

        .order-item-name {
            font-size: 14px;
            font-weight: 600;
        }

        .order-more {
            color: #707070;
            font-size: 12px;
        }

        .order-progress {
            align-self: center;
        }

        .order-progress-track {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 14px;
        }

        .order-progress-step {
            min-width: 0;
        }

        .order-progress-dot {
            width: 100%;
            height: 4px;
            background: #d9d9d9;
        }

        .order-progress-step.active .order-progress-dot {
            background: #111111;
        }

        .order-progress-label {
            display: block;
            margin-top: 7px;
            color: #707070;
            font-size: 10px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #eeeeee;
            color: #555555;
            font-size: 13px;
        }

        .order-total-row strong {
            color: #111111;
            font-size: 16px;
        }

        .order-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .order-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #111111;
            background: #111111;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .order-action.secondary {
            background: transparent;
            color: #111111;
        }

        .orders-empty {
            margin-top: 28px;
            padding: 60px 20px;
            border: 1px solid #e6e6e6;
            text-align: center;
        }

        .orders-empty h2 {
            margin: 0 0 10px;
            font-size: 22px;
            font-weight: 500;
        }

        .orders-empty p {
            margin: 0 0 20px;
            color: #707070;
        }

        .orders-pagination {
            margin-top: 28px;
        }

        body.dark-mode .orders-page {
            background: #111111;
        }

        body.dark-mode .orders-heading,
        body.dark-mode .order-card,
        body.dark-mode .orders-empty {
            border-color: #303030;
        }

        body.dark-mode .orders-count,
        body.dark-mode .order-date,
        body.dark-mode .order-message,
        body.dark-mode .order-item-meta,
        body.dark-mode .order-more,
        body.dark-mode .order-progress-label,
        body.dark-mode .orders-empty p {
            color: #bbbbbb;
        }

        body.dark-mode .order-card:hover {
            border-color: #ffffff;
        }

        body.dark-mode .order-progress-dot {
            background: #303030;
        }

        body.dark-mode .order-progress-step.active .order-progress-dot,
        body.dark-mode .order-badge {
            background: #ffffff;
            color: #111111;
        }

        body.dark-mode .order-total-row {
            border-color: #303030;
            color: #bbbbbb;
        }

        body.dark-mode .order-total-row strong,
        body.dark-mode .order-action.secondary {
            color: #ffffff;
        }

        body.dark-mode .order-action {
            border-color: #ffffff;
            background: #ffffff;
            color: #111111;
        }

        @media (max-width: 900px) {
            .orders-page {
                padding: 96px 16px 56px;
            }

            .orders-heading,
            .order-card-head {
                align-items: start;
                flex-direction: column;
            }

            .order-card {
                grid-template-columns: 1fr;
            }

            .order-progress-label {
                font-size: 9px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="orders-page">
        <div class="orders-shell">
            <div class="orders-heading">
                <h1 class="orders-title">Riwayat Pembelian</h1>
                <div class="orders-count">{{ $orders->total() }} pesanan</div>
            </div>

            @forelse ($orders as $order)
                @php
                    $activeStep = $stepForStatus[$order->status] ?? 1;
                    $badgeClass = in_array($order->status, ['completed', 'paid'], true)
                        ? 'success'
                        : (in_array($order->status, ['cancelled', 'failed', 'refunded'], true) ? 'danger' : 'warning');
                @endphp

                <article class="order-card">
                    <div>
                        <div class="order-card-head">
                            <div>
                                <h2 class="order-code">{{ $order->order_code }}</h2>
                                <div class="order-date">{{ $order->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <span class="order-badge {{ $badgeClass }}">
                                {{ $statusLabels[$order->status] ?? str_replace('_', ' ', $order->status) }}
                            </span>
                        </div>

                        <div class="order-items">
                            @foreach ($order->items->take(2) as $item)
                                @php
                                    $image = $item->product?->images?->firstWhere('is_primary', true) ?? $item->product?->images?->first();
                                @endphp
                                <div class="order-item">
                                    <img class="order-item-image"
                                        src="{{ $image ? asset('storage/' . $image->image) : 'https://via.placeholder.com/160' }}"
                                        alt="{{ $item->product->name ?? 'Produk' }}">
                                    <div>
                                        <div class="order-item-name">{{ $item->product->name ?? 'Produk' }}</div>
                                        <div class="order-item-meta">
                                            Size {{ $item->productVariant->size ?? '-' }} / Qty {{ $item->qty }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if ($order->items->count() > 2)
                                <div class="order-more">+{{ $order->items->count() - 2 }} item lainnya</div>
                            @endif
                        </div>

                        <p class="order-message">{{ $statusMessages[$order->status] ?? 'Status pesanan sedang diproses.' }}</p>

                        <div class="order-total-row">
                            <span>Total</span>
                            <strong>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</strong>
                        </div>

                        <div class="order-actions">
                            <a href="{{ route('user.orders.show', $order->order_code) }}" class="order-action secondary">
                                Detail
                            </a>
                            @if ($order->payment_url && in_array($order->status, ['quoted', 'pending', 'challenge'], true))
                                <a href="{{ $order->payment_url }}" target="_blank" rel="noopener" class="order-action">
                                    Bayar
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="order-progress">
                        <div class="order-progress-track" aria-label="Progress pesanan">
                            @foreach ($stepLabels as $index => $label)
                                <div class="order-progress-step {{ $index + 1 <= $activeStep ? 'active' : '' }}">
                                    <div class="order-progress-dot"></div>
                                    <span class="order-progress-label">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            @empty
                <div class="orders-empty">
                    <h2>Belum ada pesanan</h2>
                    <p>Pesanan yang kamu buat akan tampil di sini lengkap dengan statusnya.</p>
                    <a href="{{ route('home') }}" class="order-action">Mulai Belanja</a>
                </div>
            @endforelse

            @if ($orders->hasPages())
                <div class="orders-pagination">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
