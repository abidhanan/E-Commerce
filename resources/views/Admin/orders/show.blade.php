@extends('Admin.Template.index')

@section('title', 'Order ' . $order->order_code)

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">{{ $order->order_code }}</h4>
                <div class="text-muted">Status: {{ str_replace('_', ' ', $order->status) }}</div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Kembali</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Items</h5>
                            @if ($order->stock_deducted_at)
                                <span class="badge bg-success">Stok sudah dikurangi</span>
                            @else
                                <span class="badge bg-secondary">Stok belum dikurangi</span>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Kode Produk</th>
                                        <th>Produk</th>
                                        <th>Size</th>
                                        <th>Stok</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->id ?? '-' }}</td>
                                            <td>{{ $item->productVariant->sku ?? '-' }}</td>
                                            <td>{{ $item->product->name ?? '-' }}</td>
                                            <td>{{ $item->productVariant->size ?? '-' }}</td>
                                            <td>{{ $item->productVariant->stock ?? '-' }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Customer & Alamat</h5>
                        <p class="mb-1"><strong>{{ $order->user->name ?? '-' }}</strong></p>
                        <p class="text-muted mb-3">{{ $order->user->email ?? '-' }}</p>

                        @if ($order->address)
                            <p class="mb-1">{{ $order->address->recipient_name }} / {{ $order->address->phone_number }}
                            </p>
                            <p class="text-muted mb-0">
                                {{ $order->address->full_address }},
                                {{ $order->address->city }},
                                {{ $order->address->province }}
                                {{ $order->address->postal_code }}
                            </p>
                        @else
                            <p class="text-muted mb-0">Alamat tidak tersedia.</p>
                        @endif

                        @if ($order->customer_note)
                            <hr>
                            <p class="mb-1"><strong>Catatan Customer</strong></p>
                            <p class="text-muted mb-0">{{ $order->customer_note }}</p>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="mb-3">Rating & Komplain</h5>

                        @if ($order->review)
                            <p class="mb-1"><strong>Rating:</strong> {{ (int) $order->review->rating }}/5</p>
                            <p class="text-muted mb-3">{{ $order->review->comment ?: 'Tanpa komentar.' }}</p>
                        @else
                            <p class="text-muted mb-3">Belum ada rating dari customer.</p>
                        @endif

                        @if ($order->complaints->isNotEmpty())
                            <div class="list-group">
                                @foreach ($order->complaints as $complaint)
                                    <a href="{{ route('admin.order-complaints.show', $complaint) }}"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                                        <span>
                                            <strong>{{ $complaint->subject }}</strong><br>
                                            <small
                                                class="text-muted">{{ \Illuminate\Support\Str::limit($complaint->message, 80) }}</small>
                                        </span>
                                        <span class="badge bg-dark">{{ str_replace('_', ' ', $complaint->status) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada komplain untuk order ini.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Konfirmasi Harga</h5>
                        <form action="{{ route('admin.orders.quote', $order) }}" method="POST" data-disable-on-submit
                            data-loading-text="Mengirim konfirmasi...">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Subtotal Produk</label>
                                <input type="text" class="form-control"
                                    value="Rp {{ number_format($order->subtotal ?: $order->items->sum(fn($item) => $item->price * $item->qty), 0, ',', '.') }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ongkir</label>
                                <input type="number" name="shipping_cost" class="form-control" min="0"
                                    id="shippingCostInput" value="{{ old('shipping_cost', $order->shipping_cost ?? 0) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Total Final</label>
                                <input type="number" name="gross_amount" class="form-control" min="0"
                                    id="grossAmountInput"
                                    data-subtotal="{{ $order->subtotal ?: $order->items->sum(fn($item) => $item->price * $item->qty) }}"
                                    value="{{ old('gross_amount', $order->gross_amount) }}" required>
                                <div class="form-text">Isi total yang akan customer bayarkan, termasuk ongkir.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Link Payment Midtrans Custom</label>
                                <input type="url" name="payment_url" class="form-control"
                                    value="{{ old('payment_url', $order->payment_url) }}"
                                    placeholder="https://app.midtrans.com/payment-links/...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan Admin</label>
                                <textarea name="admin_note" class="form-control" rows="4">{{ old('admin_note', $order->admin_note) }}</textarea>
                            </div>

                            <button class="btn btn-dark w-100">Kirim Konfirmasi</button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Update Status</h5>
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select mb-3">
                                @foreach (['waiting_admin', 'quoted', 'paid', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>
                                        {{ str_replace('_', ' ', $status) }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="mb-3">
                                <label class="form-label">Estimasi sampai</label>
                                <input type="datetime-local" name="delivery_estimated_at" class="form-control"
                                    value="{{ old('delivery_estimated_at', $order->delivery_estimated_at?->format('Y-m-d\TH:i')) }}">
                                <div class="form-text">Wajib diisi saat status diubah ke shipped. Jika estimasi ini sudah
                                    lewat, sistem akan menandai order selesai saat halaman order dibuka.</div>
                            </div>

                            <div class="mb-3 small text-muted">
                                <div>Dikirim: {{ $order->shipped_at?->format('d M Y H:i') ?? '-' }}</div>
                                <div>Estimasi: {{ $order->delivery_estimated_at?->format('d M Y H:i') ?? '-' }}</div>
                                <div>Selesai: {{ $order->completed_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>

                            <button class="btn btn-outline-dark w-100">Simpan Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const shippingCostInput = document.getElementById('shippingCostInput');
        const grossAmountInput = document.getElementById('grossAmountInput');

        shippingCostInput?.addEventListener('input', () => {
            const subtotal = Number(grossAmountInput?.dataset.subtotal || 0);
            const shipping = Number(shippingCostInput.value || 0);

            if (grossAmountInput) {
                grossAmountInput.value = subtotal + shipping;
            }
        });
    </script>
@endpush
