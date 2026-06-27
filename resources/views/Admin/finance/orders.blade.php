@extends('Admin.Template.index')

@section('title', 'Laporan Transaksi')

@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

@push('styles')
    <style>
        .context-note {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface-strong);
        }

        .context-note summary {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            cursor: pointer;
            padding: 18px 20px;
            font-weight: 600;
        }

        .context-note summary::-webkit-details-marker {
            display: none;
        }

        .context-note__body {
            padding: 0 20px 20px;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .context-note__body p:last-child,
        .context-note__body ul:last-child {
            margin-bottom: 0;
        }

        .context-note__body ul {
            margin: 12px 0 0;
            padding-left: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Finance</span>
                <h1 class="admin-page-title">Laporan Transaksi</h1>
                <p class="admin-page-subtitle">Cari, filter, dan export order berdasarkan tanggal, customer, kode order, atau status pembayaran.</p>
            </div>

            <div class="admin-page-actions">
                <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-speedometer2"></i> Dashboard Finance
                </a>
                <a href="{{ route('admin.finance.export', request()->only(['search', 'status', 'date_from', 'date_to'])) }}" class="btn btn-dark">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>

        <details class="context-note mb-4">
            <summary>
                <span>Keterangan Laporan Transaksi</span>
                <i class="bi bi-plus-lg"></i>
            </summary>
            <div class="context-note__body">
                <p>Halaman ini adalah daftar order mentah untuk kebutuhan finance dan follow up pembayaran.</p>
                <ul>
                    <li>`Subtotal` diambil dari nilai produk sebelum ongkir.</li>
                    <li>`Ongkir` muncul jika admin sudah menetapkan biaya kirim pada order manual flow.</li>
                    <li>`Total` adalah `gross_amount`, yaitu nominal akhir yang dibayar customer.</li>
                    <li>`Payment link` adalah URL pembayaran yang dikembalikan oleh payment gateway.</li>
                    <li>Filter status dan tanggal langsung mempengaruhi hasil export CSV.</li>
                </ul>
            </div>
        </details>

        <div class="card p-3 p-lg-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-3">
                    <label class="form-label">Cari order/customer</label>
                    <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="ORD-..., nama, email">
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach ($statusLabels as $status => $label)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Dari tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-6 col-xl-2">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-xl-3 d-flex gap-2 justify-content-xl-end">
                    <a href="{{ route('admin.finance.orders') }}" class="btn btn-outline-dark">Reset</a>
                    <button class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>

        <div class="card p-3 p-lg-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Daftar Transaksi</h5>
                    <p class="text-muted mb-0">
                        {{ number_format($orders->total(), 0, ',', '.') }} order ditemukan.
                        @if ($from || $to)
                            Periode {{ $from?->format('d M Y') ?? 'awal' }} - {{ $to?->format('d M Y') ?? 'sekarang' }}.
                        @endif
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Ongkir</th>
                            <th class="text-end">Total</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $order->order_code }}</div>
                                    @if ($order->payment_url)
                                        <a href="{{ $order->payment_url }}" target="_blank" rel="noopener" class="small text-muted">
                                            Payment gateway link
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $order->user->name ?? '-' }}</div>
                                    <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                                </td>
                                <td><span class="badge bg-dark">{{ $statusLabels[$order->status] ?? str_replace('_', ' ', $order->status) }}</span></td>
                                <td class="text-end">{{ number_format($order->items->sum('qty'), 0, ',', '.') }}</td>
                                <td class="text-end">{{ $money($order->subtotal ?: $order->items->sum(fn ($item) => $item->price * $item->qty)) }}</td>
                                <td class="text-end">{{ $order->shipping_cost === null ? '-' : $money($order->shipping_cost) }}</td>
                                <td class="text-end fw-semibold">{{ $money($order->gross_amount) }}</td>
                                <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada transaksi sesuai filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
