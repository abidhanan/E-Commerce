@extends('Admin.Template.index')

@section('title', 'Finance')

@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $periodLabel = $from && $to
        ? $from->format('d M Y') . ' - ' . $to->format('d M Y')
        : 'Semua periode';
@endphp

@push('styles')
    <style>
        .finance-stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .finance-mini-bar {
            height: 8px;
            min-width: 44px;
            border-radius: 999px;
            background: var(--accent-soft);
            overflow: hidden;
        }

        .finance-mini-bar span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--accent);
        }

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
                <h1 class="admin-page-title">Dashboard Finance</h1>
                <p class="admin-page-subtitle">Ringkasan revenue, piutang, status pembayaran, dan order yang perlu diproses dari data order aktual.</p>
            </div>

            <div class="admin-page-actions">
                <a href="{{ route('admin.finance.orders') }}" class="btn btn-outline-dark">
                    <i class="bi bi-receipt"></i> Laporan Transaksi
                </a>
                <a href="{{ route('admin.finance.export', request()->only(['date_from', 'date_to'])) }}" class="btn btn-dark">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>

        <details class="context-note mb-4">
            <summary>
                <span>Keterangan Metrik Finance</span>
                <i class="bi bi-plus-lg"></i>
            </summary>
            <div class="context-note__body">
                <p>Semua angka di dashboard ini dihitung dari tabel `orders` dan `order_items` yang ada sekarang, jadi nilainya mengikuti status order aktual.</p>
                <ul>
                    <li>`Revenue paid total` menjumlahkan order dengan status `paid`, `processing`, `shipped`, dan `completed`.</li>
                    <li>`Revenue periode` memakai filter tanggal di atas, tetapi hanya menghitung order pada alur paid flow.</li>
                    <li>`Piutang aktif` adalah order yang sudah dikutip atau sedang menunggu pembayaran: `quoted`, `pending`, dan `challenge`.</li>
                    <li>`Butuh quote` adalah order dengan status `waiting_admin` yang belum dikirim nominal final ke customer.</li>
                    <li>`Status pembayaran periode` menunjukkan jumlah order dan nominal per status pada rentang tanggal yang sedang dipilih.</li>
                </ul>
            </div>
        </details>

        <div class="card p-3 p-lg-4 mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4 col-xl-3">
                    <label class="form-label">Dari tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from', $from?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 col-xl-3">
                    <label class="form-label">Sampai tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to', $to?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 col-xl-6 d-flex gap-2 justify-content-md-end">
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-dark">Reset</a>
                    <button class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Revenue paid total</small>
                            <h3 class="mt-2 mb-1">{{ $money($summary['revenue_total']) }}</h3>
                            <span class="text-muted small">Status paid, processing, shipped, completed</span>
                        </div>
                        <span class="finance-stat-icon"><i class="bi bi-cash-stack"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Revenue periode</small>
                            <h3 class="mt-2 mb-1">{{ $money($summary['revenue_period']) }}</h3>
                            <span class="text-muted small">{{ $periodLabel }}</span>
                        </div>
                        <span class="finance-stat-icon"><i class="bi bi-calendar2-check"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Piutang aktif</small>
                            <h3 class="mt-2 mb-1">{{ $money($summary['receivables_total']) }}</h3>
                            <span class="text-muted small">Quoted, pending, challenge</span>
                        </div>
                        <span class="finance-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Butuh quote</small>
                            <h3 class="mt-2 mb-1">{{ number_format($summary['needs_quote_count'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Order waiting admin</span>
                        </div>
                        <span class="finance-stat-icon"><i class="bi bi-exclamation-circle"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="card h-100 p-3 p-lg-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="mb-1">Status Pembayaran Periode</h5>
                            <p class="text-muted mb-0">{{ number_format($summary['orders_period_count'], 0, ',', '.') }} order pada {{ $periodLabel }}.</p>
                        </div>
                        <span class="badge bg-dark align-self-md-start">{{ number_format($summary['paid_period_count'], 0, ',', '.') }} paid flow</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th class="text-end">Order</th>
                                    <th class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($statusSummary as $row)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $row['label'] }}</span></td>
                                        <td class="text-end">{{ number_format($row['count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $money($row['amount']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada order pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card h-100 p-3 p-lg-4">
                    <h5 class="mb-1">Revenue 6 Bulan</h5>
                    <p class="text-muted mb-3">Menggunakan order dengan status paid sampai completed.</p>

                    @php
                        $maxRevenue = max((float) $monthlyRevenue->max('amount'), 1);
                    @endphp

                    <div class="d-flex flex-column gap-3">
                        @foreach ($monthlyRevenue as $month)
                            <div class="d-flex align-items-center gap-3">
                                <div class="small fw-semibold" style="width: 76px;">{{ $month['label'] }}</div>
                                <div class="finance-mini-bar flex-grow-1">
                                    <span style="width: {{ min(100, ((float) $month['amount'] / $maxRevenue) * 100) }}%;"></span>
                                </div>
                                <div class="small text-end" style="width: 120px;">{{ $money($month['amount']) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card h-100 p-3 p-lg-4">
                    <h5 class="mb-1">Produk Teratas Periode</h5>
                    <p class="text-muted mb-3">Produk dengan revenue terbesar dari order yang sudah masuk paid flow.</p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topProducts as $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product['name'] }}</td>
                                        <td class="text-end">{{ number_format($product['qty'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $money($product['amount']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada produk terjual pada periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card h-100 p-3 p-lg-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                        <div>
                            <h5 class="mb-1">Order Perlu Follow Up</h5>
                            <p class="text-muted mb-0">Waiting admin, quoted, pending, dan challenge terbaru.</p>
                        </div>
                        <a href="{{ route('admin.finance.orders', ['status' => 'waiting_admin']) }}" class="btn btn-outline-dark btn-sm align-self-md-start">
                            Lihat Waiting
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attentionOrders as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->order_code }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td><span class="badge bg-dark">{{ $statusLabels[$order->status] ?? str_replace('_', ' ', $order->status) }}</span></td>
                                        <td class="text-end">{{ $money($order->gross_amount) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Tidak ada order yang perlu follow up.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
