@extends('Users.Template.index')

@section('title', 'Review Order')

@push('css')
    <style>
        .checkout-review-page {
            min-height: 70vh;
            padding: 120px 32px 80px;
            background: #fff;
        }

        .checkout-review-shell {
            width: min(1180px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 32px;
        }

        .checkout-review-title {
            grid-column: 1 / -1;
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 20px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e6e6e6;
        }

        .checkout-review-title h1 {
            font-size: 34px;
            font-weight: 400;
            line-height: 1.15;
        }

        .checkout-review-title span {
            color: #707070;
            font-size: 13px;
        }

        .checkout-section {
            padding: 22px 0;
            border-bottom: 1px solid #e6e6e6;
        }

        .checkout-section-title {
            margin-bottom: 16px;
            font-size: 16px;
            font-weight: 600;
        }

        .checkout-section-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .checkout-section-title-row .checkout-section-title {
            margin-bottom: 0;
        }

        .checkout-address-switch,
        .checkout-manage-address {
            border: 0;
            background: transparent;
            color: #111;
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .checkout-address-preview {
            padding: 16px;
            border: 1px solid #dedede;
            background: #fafafa;
        }

        .checkout-item {
            display: grid;
            grid-template-columns: 86px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .checkout-item:last-child {
            border-bottom: 0;
        }

        .checkout-item-image {
            width: 86px;
            aspect-ratio: 1;
            object-fit: cover;
            background: #f0f0f0;
        }

        .checkout-item-name {
            font-size: 14px;
            font-weight: 600;
        }

        .checkout-item-meta {
            margin-top: 4px;
            color: #777;
            font-size: 12px;
        }

        .checkout-stock-warning {
            display: block;
            margin-top: 4px;
            color: #b02a37;
        }

        .checkout-address-list {
            display: grid;
            gap: 10px;
        }

        .checkout-address-list[hidden] {
            display: none;
        }

        .checkout-address-option {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
            padding: 14px;
            border: 1px solid #dedede;
            cursor: pointer;
        }

        .checkout-address-name {
            font-size: 14px;
            font-weight: 600;
        }

        .checkout-address-text {
            margin-top: 5px;
            color: #666;
            font-size: 12px;
            line-height: 1.55;
        }

        .checkout-address-note {
            display: block;
            margin-top: 5px;
            color: #777;
            font-size: 12px;
        }

        .checkout-note {
            width: 100%;
            min-height: 92px;
            border: 1px solid #d9d9d9;
            padding: 12px;
            font: inherit;
            resize: vertical;
        }

        .checkout-summary {
            position: sticky;
            top: 104px;
            align-self: start;
            padding: 22px;
            border: 1px solid #dedede;
        }

        .checkout-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 12px;
            color: #555;
            font-size: 14px;
        }

        .checkout-summary-total {
            padding-top: 14px;
            border-top: 1px solid #e6e6e6;
            color: #111;
            font-size: 18px;
            font-weight: 600;
        }

        .checkout-summary-help {
            margin: 16px 0;
            color: #777;
            font-size: 12px;
            line-height: 1.65;
        }

        .checkout-submit,
        .checkout-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 46px;
            border: 0;
            background: #111;
            color: #fff;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            cursor: pointer;
        }

        .checkout-link {
            background: #f1f1f1;
            color: #111;
        }

        .checkout-empty {
            grid-column: 1 / -1;
            padding: 80px 20px;
            text-align: center;
            border: 1px solid #e6e6e6;
            background: #fafafa;
        }

        .checkout-alert {
            grid-column: 1 / -1;
            padding: 14px 16px;
            border: 1px solid #f0c6c6;
            background: #fff5f5;
            color: #9f1d1d;
            font-size: 13px;
            line-height: 1.6;
        }

        body.dark-mode .checkout-review-page {
            background: #111;
        }

        body.dark-mode .checkout-review-title,
        body.dark-mode .checkout-section,
        body.dark-mode .checkout-summary,
        body.dark-mode .checkout-address-preview,
        body.dark-mode .checkout-address-option,
        body.dark-mode .checkout-note,
        body.dark-mode .checkout-empty {
            border-color: #303030;
        }

        body.dark-mode .checkout-item {
            border-color: #252525;
        }

        body.dark-mode .checkout-review-title span,
        body.dark-mode .checkout-item-meta,
        body.dark-mode .checkout-address-text,
        body.dark-mode .checkout-address-note,
        body.dark-mode .checkout-summary-row,
        body.dark-mode .checkout-summary-help {
            color: #bbb;
        }

        body.dark-mode .checkout-summary-total {
            color: #f1f1f1;
        }

        body.dark-mode .checkout-note,
        body.dark-mode .checkout-address-preview,
        body.dark-mode .checkout-empty {
            background: #171717;
            color: #f1f1f1;
        }

        body.dark-mode .checkout-address-switch,
        body.dark-mode .checkout-manage-address {
            color: #f1f1f1;
        }

        body.dark-mode .checkout-submit {
            background: #fff;
            color: #111;
        }

        @media (max-width: 900px) {
            .checkout-review-page {
                padding: 96px 16px 56px;
            }

            .checkout-review-shell {
                grid-template-columns: 1fr;
            }

            .checkout-summary {
                position: static;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $hasStockIssue = $items->contains(
            fn($item) => blank($item['variant']->size) || (int) $item['variant']->stock < (int) $item['qty'],
        );
        $defaultAddress = $addresses->firstWhere('is_primary', true) ?? $addresses->first();
        $selectedAddressId = old('address_id', $defaultAddress?->id);
        $selectedAddress = $addresses->firstWhere('id', (int) $selectedAddressId) ?? $defaultAddress;
    @endphp

    <section class="checkout-review-page">
        <form action="{{ route('checkout.order') }}" method="POST" class="checkout-review-shell" data-disable-on-submit
            data-loading-text="Memproses pesanan...">
            @csrf
            <input type="hidden" name="source" value="{{ $source }}">
            @if ($variantId)
                <input type="hidden" name="variant_id" value="{{ $variantId }}">
            @endif

            <div class="checkout-review-title">
                <h1>Review Order</h1>
                <span>{{ $items->sum('qty') }} item{{ $items->sum('qty') === 1 ? '' : 's' }}</span>
            </div>

            @if ($errors->any())
                <div class="checkout-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            @if ($items->isEmpty())
                <div class="checkout-empty">
                    <h2>Cart masih kosong</h2>
                    <p>Tambahkan produk ke cart dulu sebelum membuat pesanan.</p>
                    <a href="{{ route('home') }}" class="checkout-link">Browse Products</a>
                </div>
            @else
                <div>
                    <div class="checkout-section">
                        <div class="checkout-section-title">Items</div>
                        @foreach ($items as $item)
                            @php
                                $image = collect($item['product']->images)->firstWhere('is_primary', 1) ?? $item['product']->images->first();
                            @endphp
                            <div class="checkout-item">
                                <img class="checkout-item-image"
                                    src="{{ $image ? asset('storage/' . $image->image) : 'https://via.placeholder.com/160' }}"
                                    alt="{{ $item['product']->name }}">
                                <div>
                                    <div class="checkout-item-name">{{ $item['product']->name }}</div>
                                    <div class="checkout-item-meta">
                                        Size {{ $item['variant']->size }} / Qty {{ $item['qty'] }}
                                        @if (blank($item['variant']->size))
                                            <span class="checkout-stock-warning">Size produk ini belum valid.</span>
                                        @elseif ((int) $item['variant']->stock < (int) $item['qty'])
                                            <span class="checkout-stock-warning">
                                                Stok size {{ $item['variant']->size }} tinggal {{ (int) $item['variant']->stock }}.
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>Rp {{ number_format($item['line_total'], 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="checkout-section">
                        <div class="checkout-section-title-row">
                            <div class="checkout-section-title">Alamat Pengiriman</div>
                            @if ($addresses->isNotEmpty())
                                <button type="button" class="checkout-address-switch" data-toggle-checkout-address>
                                    Ganti Alamat
                                </button>
                            @endif
                        </div>
                        @if ($addresses->isEmpty())
                            <p>Belum ada alamat tersimpan.</p>
                            <a href="{{ route('account.index') }}" class="checkout-link">Tambah Alamat</a>
                        @else
                            <div class="checkout-address-preview" id="checkoutAddressPreview">
                                <div class="checkout-address-name" data-address-preview-name>
                                    {{ $selectedAddress->label ?: 'Alamat' }} - {{ $selectedAddress->recipient_name }}
                                </div>
                                <div class="checkout-address-text" data-address-preview-text>
                                    {{ $selectedAddress->phone_number }}<br>
                                    {{ $selectedAddress->full_address }},
                                    {{ $selectedAddress->city }}, {{ $selectedAddress->province }}
                                    {{ $selectedAddress->postal_code }}
                                </div>
                                @if ($selectedAddress->note)
                                    <span class="checkout-address-note" data-address-preview-note>
                                        {{ $selectedAddress->note }}
                                    </span>
                                @else
                                    <span class="checkout-address-note" data-address-preview-note hidden></span>
                                @endif
                            </div>

                            <div class="checkout-address-list" id="checkoutAddressList" hidden>
                                @foreach ($addresses as $address)
                                    <label class="checkout-address-option">
                                        <input type="radio" name="address_id" value="{{ $address->id }}"
                                            data-label="{{ $address->label ?: 'Alamat' }}"
                                            data-recipient="{{ $address->recipient_name }}"
                                            data-phone="{{ $address->phone_number }}"
                                            data-address="{{ $address->full_address }}"
                                            data-city="{{ $address->city }}"
                                            data-province="{{ $address->province }}"
                                            data-postal="{{ $address->postal_code }}"
                                            data-note="{{ $address->note }}"
                                            @checked($selectedAddressId == $address->id)>
                                        <span>
                                            <span class="checkout-address-name">
                                                {{ $address->label ?: 'Alamat' }} - {{ $address->recipient_name }}
                                            </span>
                                            <span class="checkout-address-text">
                                                {{ $address->phone_number }}<br>
                                                {{ $address->full_address }},
                                                {{ $address->city }}, {{ $address->province }}
                                                {{ $address->postal_code }}
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                                <a href="{{ route('account.index') }}#addressPreviewSection" class="checkout-manage-address">
                                    Kelola atau tambah alamat
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="checkout-section">
                        <div class="checkout-section-title">Catatan</div>
                        <textarea name="customer_note" class="checkout-note" placeholder="Catatan untuk admin atau pengiriman">{{ old('customer_note') }}</textarea>
                    </div>
                </div>

                <aside class="checkout-summary">
                    <div class="checkout-section-title">Summary</div>
                    <div class="checkout-summary-row">
                        <span>Subtotal produk</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="checkout-summary-row">
                        <span>Ongkir</span>
                        <strong>Menunggu admin</strong>
                    </div>
                    <div class="checkout-summary-row checkout-summary-total">
                        <span>Estimasi awal</span>
                        <strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <p class="checkout-summary-help">
                        @if ($hasStockIssue)
                            Ada item dengan size atau stok yang belum valid. Perbarui cart sebelum membuat pesanan.
                        @else
                            Setelah klik pesan, admin akan mengecek alamat, ongkir, dan total akhir. Link pembayaran Midtrans
                            custom akan dikirim lewat detail pesanan setelah admin konfirmasi.
                        @endif
                    </p>
                    <button type="submit" class="checkout-submit" @disabled($addresses->isEmpty() || $hasStockIssue)>Pesan</button>
                </aside>
            @endif
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.querySelector('[data-toggle-checkout-address]');
            const addressList = document.getElementById('checkoutAddressList');
            const previewName = document.querySelector('[data-address-preview-name]');
            const previewText = document.querySelector('[data-address-preview-text]');
            const previewNote = document.querySelector('[data-address-preview-note]');

            toggleButton?.addEventListener('click', () => {
                if (!addressList) return;

                const willOpen = addressList.hasAttribute('hidden');
                addressList.toggleAttribute('hidden', !willOpen);
                toggleButton.textContent = willOpen ? 'Tutup Pilihan' : 'Ganti Alamat';
            });

            document.querySelectorAll('input[name="address_id"]').forEach((input) => {
                input.addEventListener('change', () => {
                    if (previewName) {
                        previewName.textContent = `${input.dataset.label || 'Alamat'} - ${input.dataset.recipient || '-'}`;
                    }

                    if (previewText) {
                        previewText.replaceChildren(
                            document.createTextNode(input.dataset.phone || '-'),
                            document.createElement('br'),
                            document.createTextNode(`${input.dataset.address || '-'}, ${input.dataset.city || ''}, ${input.dataset.province || ''} ${input.dataset.postal || ''}`),
                        );
                    }

                    if (previewNote) {
                        previewNote.textContent = input.dataset.note || '';
                        previewNote.toggleAttribute('hidden', !input.dataset.note);
                    }

                    addressList?.setAttribute('hidden', 'hidden');
                    if (toggleButton) {
                        toggleButton.textContent = 'Ganti Alamat';
                    }
                });
            });
        });
    </script>
@endpush
