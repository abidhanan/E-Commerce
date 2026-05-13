@extends('Users.Template.index')

@section('title', 'Detail Pesanan ' . $order->order_code)

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
        'waiting_admin' => 'Pesanan kamu sudah masuk ke admin. Tunggu admin mengecek alamat, ongkir, dan membuat link pembayaran Midtrans.',
        'quoted' => 'Admin sudah mengonfirmasi total akhir. Gunakan link pembayaran untuk menyelesaikan transaksi.',
        'pending' => 'Pembayaran masih menunggu penyelesaian. Jika sudah bayar, tunggu callback Midtrans dan refresh status.',
        'challenge' => 'Pembayaran sedang direview oleh Midtrans atau bank.',
        'paid' => 'Pembayaran sudah diterima. Pesanan akan diproses admin.',
        'processing' => 'Pesanan sedang diproses.',
        'shipped' => 'Pesanan sedang dikirim. Jika barang sudah sampai, kamu bisa menandai pesanan selesai.',
        'completed' => 'Pesanan selesai.',
        'cancelled' => 'Pesanan dibatalkan.',
        'failed' => 'Pembayaran gagal atau transaksi kedaluwarsa.',
        'refunded' => 'Pembayaran sudah dikembalikan.',
    ];

    $timeline = [
        ['key' => 'created', 'label' => 'Pesanan dibuat', 'status' => 'done', 'time' => $order->created_at],
        [
            'key' => 'admin',
            'label' => 'Admin cek pesanan',
            'status' => in_array($order->status, ['quoted', 'pending', 'challenge', 'paid', 'processing', 'shipped', 'completed'], true) ? 'done' : 'current',
            'time' => $order->quoted_at,
        ],
        [
            'key' => 'payment',
            'label' => 'Pembayaran',
            'status' => in_array($order->status, ['paid', 'processing', 'shipped', 'completed'], true)
                ? 'done'
                : (in_array($order->status, ['quoted', 'pending', 'challenge'], true) ? 'current' : 'upcoming'),
            'time' => null,
        ],
        [
            'key' => 'process',
            'label' => 'Diproses',
            'status' => in_array($order->status, ['processing', 'shipped', 'completed'], true)
                ? ($order->status === 'processing' ? 'current' : 'done')
                : 'upcoming',
            'time' => null,
        ],
        [
            'key' => 'ship',
            'label' => 'Dikirim',
            'status' => in_array($order->status, ['shipped', 'completed'], true)
                ? ($order->status === 'shipped' ? 'current' : 'done')
                : 'upcoming',
            'time' => $order->shipped_at,
        ],
        [
            'key' => 'complete',
            'label' => 'Selesai',
            'status' => $order->status === 'completed' ? 'done' : 'upcoming',
            'time' => $order->completed_at,
        ],
    ];

    if (in_array($order->status, ['cancelled', 'failed', 'refunded'], true)) {
        $timeline[] = [
            'key' => 'terminal',
            'label' => $statusLabels[$order->status],
            'status' => 'danger',
            'time' => $order->updated_at,
        ];
    }

    $canConfirmReceived = $order->status === 'shipped';
    $canReview = $order->status === 'completed';
    $canComplain = in_array($order->status, ['shipped', 'completed'], true);
@endphp

@push('css')
    <style>
        .order-detail-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #ffffff;
        }

        .order-detail-shell {
            width: min(1120px, 100%);
            margin: 0 auto;
        }

        .order-detail-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e6e6e6;
        }

        .order-detail-title {
            margin: 0;
            font-size: 32px;
            font-weight: 400;
            line-height: 1.15;
        }

        .order-detail-date {
            margin-top: 8px;
            color: #707070;
            font-size: 13px;
        }

        .order-detail-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 14px;
            border: 1px solid #111111;
            background: transparent;
            color: #111111;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .order-detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 32px;
            margin-top: 30px;
        }

        .order-detail-section {
            padding: 22px 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .order-detail-section:first-child {
            padding-top: 0;
        }

        .order-section-title {
            margin: 0 0 16px;
            font-size: 16px;
            font-weight: 700;
        }

        .order-status-box {
            padding: 18px;
            border: 1px solid #dedede;
            background: #fafafa;
        }

        .order-status-badge {
            display: inline-flex;
            min-height: 30px;
            align-items: center;
            padding: 0 10px;
            background: #111111;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .order-status-copy {
            margin: 12px 0 0;
            color: #555555;
            font-size: 14px;
            line-height: 1.7;
        }

        .order-alert {
            margin-top: 18px;
            padding: 14px 16px;
            border: 1px solid #dedede;
            background: #fafafa;
            color: #555555;
            font-size: 13px;
            line-height: 1.6;
        }

        .order-alert.success {
            border-color: #c9e6c9;
            background: #f1fbf1;
            color: #166534;
        }

        .order-alert.error {
            border-color: #f0c6c6;
            background: #fff5f5;
            color: #9f1d1d;
        }

        .order-timeline {
            display: grid;
            gap: 0;
            margin-top: 18px;
        }

        .order-timeline-step {
            position: relative;
            display: grid;
            grid-template-columns: 22px 1fr;
            gap: 12px;
            padding-bottom: 18px;
        }

        .order-timeline-step::before {
            content: '';
            position: absolute;
            top: 18px;
            bottom: 0;
            left: 8px;
            width: 1px;
            background: #dedede;
        }

        .order-timeline-step:last-child {
            padding-bottom: 0;
        }

        .order-timeline-step:last-child::before {
            display: none;
        }

        .order-timeline-dot {
            width: 18px;
            height: 18px;
            border: 1px solid #bdbdbd;
            background: #ffffff;
        }

        .order-timeline-step.done .order-timeline-dot,
        .order-timeline-step.current .order-timeline-dot {
            border-color: #111111;
            background: #111111;
        }

        .order-timeline-step.danger .order-timeline-dot {
            border-color: #9f1d1d;
            background: #9f1d1d;
        }

        .order-timeline-label {
            font-size: 14px;
            font-weight: 700;
        }

        .order-timeline-time {
            margin-top: 4px;
            color: #707070;
            font-size: 12px;
        }

        .order-detail-item {
            display: grid;
            grid-template-columns: 82px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .order-detail-item:last-child {
            border-bottom: 0;
        }

        .order-detail-image {
            width: 82px;
            aspect-ratio: 1;
            object-fit: cover;
            background: #f0f0f0;
        }

        .order-detail-name {
            font-size: 14px;
            font-weight: 700;
        }

        .order-detail-meta,
        .order-address,
        .order-note {
            margin-top: 5px;
            color: #707070;
            font-size: 13px;
            line-height: 1.6;
        }

        .order-summary {
            position: sticky;
            top: 104px;
            align-self: start;
            padding: 22px;
            border: 1px solid #dedede;
        }

        .order-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 12px;
            color: #555555;
            font-size: 14px;
        }

        .order-summary-row.total {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e6e6e6;
            color: #111111;
            font-size: 18px;
            font-weight: 700;
        }

        .order-summary-actions {
            display: grid;
            gap: 10px;
            margin-top: 20px;
        }

        .order-summary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            border: 1px solid #111111;
            background: #111111;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        .order-summary-action.secondary {
            background: transparent;
            color: #111111;
        }

        .order-summary-button {
            min-height: 44px;
            border: 1px solid #111111;
            background: #111111;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            width: 100%;
        }

        .order-feedback-card {
            padding: 18px;
            border: 1px solid #dedede;
            background: #fafafa;
        }

        .order-feedback-card + .order-feedback-card {
            margin-top: 16px;
        }

        .order-form-grid {
            display: grid;
            gap: 12px;
        }

        .order-form-field {
            display: grid;
            gap: 6px;
        }

        .order-form-field label {
            font-size: 12px;
            font-weight: 700;
        }

        .order-input,
        .order-textarea,
        .order-select {
            width: 100%;
            border: 1px solid #d9d9d9;
            background: #ffffff;
            color: #111111;
            font: inherit;
            font-size: 14px;
            padding: 11px 12px;
        }

        .order-textarea {
            min-height: 110px;
            resize: vertical;
        }

        .order-submit {
            min-height: 44px;
            border: 0;
            background: #111111;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .order-review-display {
            color: #555555;
            font-size: 14px;
            line-height: 1.7;
        }

        .order-complaint-list {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .order-complaint-card {
            padding: 14px;
            border: 1px solid #dedede;
            background: #ffffff;
        }

        .order-complaint-head {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
        }

        .order-complaint-status {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .order-complaint-photo-grid {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .order-complaint-photo {
            width: 74px;
            height: 74px;
            object-fit: cover;
            border: 1px solid #dedede;
        }

        body.dark-mode .order-detail-page {
            background: #111111;
        }

        body.dark-mode .order-detail-heading,
        body.dark-mode .order-detail-section,
        body.dark-mode .order-status-box,
        body.dark-mode .order-alert,
        body.dark-mode .order-feedback-card,
        body.dark-mode .order-complaint-card,
        body.dark-mode .order-summary {
            border-color: #303030;
        }

        body.dark-mode .order-status-box,
        body.dark-mode .order-alert,
        body.dark-mode .order-feedback-card {
            background: #171717;
        }

        body.dark-mode .order-detail-date,
        body.dark-mode .order-status-copy,
        body.dark-mode .order-timeline-time,
        body.dark-mode .order-detail-meta,
        body.dark-mode .order-address,
        body.dark-mode .order-note,
        body.dark-mode .order-review-display,
        body.dark-mode .order-summary-row {
            color: #bbbbbb;
        }

        body.dark-mode .order-timeline-step::before,
        body.dark-mode .order-detail-item,
        body.dark-mode .order-summary-row.total {
            border-color: #303030;
        }

        body.dark-mode .order-timeline-dot {
            border-color: #555555;
            background: #111111;
        }

        body.dark-mode .order-timeline-step.done .order-timeline-dot,
        body.dark-mode .order-timeline-step.current .order-timeline-dot,
        body.dark-mode .order-status-badge,
        body.dark-mode .order-summary-action,
        body.dark-mode .order-summary-button,
        body.dark-mode .order-submit {
            border-color: #ffffff;
            background: #ffffff;
            color: #111111;
        }

        body.dark-mode .order-summary-row.total,
        body.dark-mode .order-detail-link,
        body.dark-mode .order-summary-action.secondary {
            color: #ffffff;
        }

        body.dark-mode .order-detail-link,
        body.dark-mode .order-summary-action.secondary {
            border-color: #ffffff;
            background: transparent;
        }

        body.dark-mode .order-input,
        body.dark-mode .order-textarea,
        body.dark-mode .order-select,
        body.dark-mode .order-complaint-card {
            border-color: #303030;
            background: #111111;
            color: #ffffff;
        }

        @media (max-width: 900px) {
            .order-detail-page {
                padding: 96px 16px 56px;
            }

            .order-detail-heading {
                align-items: start;
                flex-direction: column;
            }

            .order-detail-grid,
            .order-detail-item {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
@endpush

@section('content')
    <section class="order-detail-page">
        <div class="order-detail-shell">
            <div class="order-detail-heading">
                <div>
                    <h1 class="order-detail-title">{{ $order->order_code }}</h1>
                    <div class="order-detail-date">{{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
                <a href="{{ route('user.orders.index') }}" class="order-detail-link">Kembali ke Riwayat</a>
            </div>

            @if (session('success'))
                <div class="order-alert success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="order-alert error">{{ $errors->first() }}</div>
            @endif

            <div class="order-detail-grid">
                <div>
                    <div class="order-detail-section">
                        <div class="order-status-box">
                            <span class="order-status-badge">
                                {{ $statusLabels[$order->status] ?? str_replace('_', ' ', $order->status) }}
                            </span>
                            <p class="order-status-copy">
                                {{ $statusMessages[$order->status] ?? 'Status pesanan sedang diproses.' }}
                            </p>
                            @if ($order->status === 'shipped' && $order->delivery_estimated_at)
                                <p class="order-status-copy">
                                    Estimasi sampai: {{ $order->delivery_estimated_at->format('d M Y H:i') }}.
                                    Jika barang sudah sampai lebih dulu, tandai pesanan selesai dari tombol di ringkasan.
                                </p>
                            @endif
                        </div>

                        <div class="order-timeline">
                            @foreach ($timeline as $step)
                                <div class="order-timeline-step {{ $step['status'] }}">
                                    <span class="order-timeline-dot"></span>
                                    <div>
                                        <div class="order-timeline-label">{{ $step['label'] }}</div>
                                        <div class="order-timeline-time">
                                            @if ($step['time'])
                                                {{ $step['time']->format('d M Y H:i') }}
                                            @elseif ($step['status'] === 'current')
                                                Status saat ini
                                            @else
                                                Menunggu
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="order-detail-section">
                        <h2 class="order-section-title">Produk Dibeli</h2>
                        @foreach ($order->items as $item)
                            @php
                                $image = $item->product?->images?->firstWhere('is_primary', true) ?? $item->product?->images?->first();
                            @endphp
                            <div class="order-detail-item">
                                <img class="order-detail-image"
                                    src="{{ $image ? asset('storage/' . $image->image) : 'https://via.placeholder.com/160' }}"
                                    alt="{{ $item->product->name ?? 'Produk' }}">
                                <div>
                                    <div class="order-detail-name">{{ $item->product->name ?? 'Produk' }}</div>
                                    <div class="order-detail-meta">
                                        Size {{ $item->productVariant->size ?? '-' }} / Qty {{ $item->qty }}
                                    </div>
                                </div>
                                <div>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-detail-section">
                        <h2 class="order-section-title">Alamat Pengiriman</h2>
                        @if ($order->address)
                            <div class="order-address">
                                <strong>{{ $order->address->recipient_name }} / {{ $order->address->phone_number }}</strong><br>
                                {{ $order->address->full_address }},
                                {{ $order->address->city }},
                                {{ $order->address->province }}
                                {{ $order->address->postal_code }}
                                @if ($order->address->note)
                                    <br>Catatan: {{ $order->address->note }}
                                @endif
                            </div>
                        @else
                            <div class="order-address">Alamat tidak tersedia.</div>
                        @endif
                    </div>

                    @if ($order->customer_note || $order->admin_note)
                        <div class="order-detail-section">
                            <h2 class="order-section-title">Catatan</h2>
                            @if ($order->customer_note)
                                <div class="order-note"><strong>Catatan kamu:</strong> {{ $order->customer_note }}</div>
                            @endif
                            @if ($order->admin_note)
                                <div class="order-note"><strong>Catatan admin:</strong> {{ $order->admin_note }}</div>
                            @endif
                        </div>
                    @endif

                    @if ($canReview || $canComplain || $order->review || $order->complaints->isNotEmpty())
                        <div class="order-detail-section">
                            <h2 class="order-section-title">Rating & Komplain</h2>

                            @if ($order->review)
                                <div class="order-feedback-card">
                                    <div class="order-review-display">
                                        <strong>Rating kamu:</strong> {{ (int) $order->review->rating }}/5<br>
                                        {{ $order->review->comment ?: 'Tanpa komentar.' }}
                                    </div>
                                </div>
                            @elseif ($canReview)
                                <form action="{{ route('user.orders.review', $order->order_code) }}" method="POST"
                                    class="order-feedback-card order-form-grid">
                                    @csrf
                                    <div class="order-form-field">
                                        <label for="rating">Rating pesanan</label>
                                        <select name="rating" id="rating" class="order-select" required>
                                            <option value="">Pilih rating</option>
                                            @for ($rating = 5; $rating >= 1; $rating--)
                                                <option value="{{ $rating }}" @selected(old('rating') == $rating)>
                                                    {{ $rating }}/5
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="order-form-field">
                                        <label for="comment">Ulasan</label>
                                        <textarea name="comment" id="comment" class="order-textarea"
                                            placeholder="Ceritakan pengalaman kamu dengan pesanan ini">{{ old('comment') }}</textarea>
                                    </div>
                                    <button class="order-submit">Simpan Rating</button>
                                </form>
                            @endif

                            @if ($canComplain)
                                <form action="{{ route('user.orders.complaints.store', $order->order_code) }}" method="POST"
                                    enctype="multipart/form-data" class="order-feedback-card order-form-grid">
                                    @csrf
                                    <div class="order-form-field">
                                        <label for="subject">Judul komplain</label>
                                        <input type="text" name="subject" id="subject" class="order-input"
                                            value="{{ old('subject') }}" placeholder="Contoh: Barang rusak saat diterima" required>
                                    </div>
                                    <div class="order-form-field">
                                        <label for="message">Detail komplain</label>
                                        <textarea name="message" id="message" class="order-textarea"
                                            placeholder="Jelaskan masalah yang perlu admin cek" required>{{ old('message') }}</textarea>
                                    </div>
                                    <div class="order-form-field">
                                        <label for="photos">Foto pendukung</label>
                                        <input type="file" name="photos[]" id="photos" class="order-input" accept="image/*"
                                            multiple>
                                    </div>
                                    <button class="order-submit">Kirim Komplain</button>
                                </form>
                            @endif

                            @if ($order->complaints->isNotEmpty())
                                <div class="order-complaint-list">
                                    @foreach ($order->complaints as $complaint)
                                        <div class="order-complaint-card">
                                            <div class="order-complaint-head">
                                                <strong>{{ $complaint->subject }}</strong>
                                                <span class="order-complaint-status">{{ str_replace('_', ' ', $complaint->status) }}</span>
                                            </div>
                                            <div class="order-note">{{ $complaint->message }}</div>
                                            @if ($complaint->admin_response)
                                                <div class="order-note"><strong>Respons admin:</strong> {{ $complaint->admin_response }}</div>
                                            @endif
                                            @if ($complaint->photos->isNotEmpty())
                                                <div class="order-complaint-photo-grid">
                                                    @foreach ($complaint->photos as $photo)
                                                        <a href="{{ asset('storage/' . $photo->path) }}" target="_blank" rel="noopener">
                                                            <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto komplain"
                                                                class="order-complaint-photo">
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <aside class="order-summary">
                    <h2 class="order-section-title">Ringkasan</h2>
                    <div class="order-summary-row">
                        <span>Subtotal produk</span>
                        <strong>Rp {{ number_format($order->subtotal ?: $order->items->sum(fn($item) => $item->price * $item->qty), 0, ',', '.') }}</strong>
                    </div>
                    <div class="order-summary-row">
                        <span>Ongkir</span>
                        <strong>{{ is_null($order->shipping_cost) ? 'Menunggu admin' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}</strong>
                    </div>
                    <div class="order-summary-row total">
                        <span>Total</span>
                        <strong>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</strong>
                    </div>

                    <div class="order-summary-actions">
                        @if ($order->payment_url && in_array($order->status, ['quoted', 'pending', 'challenge'], true))
                            <a href="{{ $order->payment_url }}" target="_blank" rel="noopener" class="order-summary-action">
                                Bayar via Midtrans
                            </a>
                        @endif
                        @if ($canConfirmReceived)
                            <form action="{{ route('user.orders.complete', $order->order_code) }}" method="POST">
                                @csrf
                                <button class="order-summary-button">Barang Sudah Diterima</button>
                            </form>
                        @endif
                        <a href="{{ route('payments.status', $order->order_code) }}" class="order-summary-action secondary">
                            Lihat Status Pembayaran
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
