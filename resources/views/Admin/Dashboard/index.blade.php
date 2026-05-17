@extends('Admin.Template.index')

@section('title', 'Dashboard')

@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

@push('styles')
    <style>
        .admin-stat-card {
            position: relative;
            overflow: hidden;
        }

        .admin-stat-card::after {
            content: "";
            position: absolute;
            inset: auto -40px -40px auto;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: var(--soft-bg);
        }

        .admin-stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--soft-bg);
            color: var(--accent);
            font-size: 1.35rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Overview</span>
                <h1 class="admin-page-title">Dashboard</h1>
                <p class="admin-page-subtitle">Ringkasan operasional admin yang dihitung langsung dari data user, order, produk, dan aktivitas login.</p>
            </div>

            <div class="admin-page-actions">
                @if (auth()->user()?->hasAnyRole(['superadmin', 'admin', 'finance']))
                    <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-cash-coin"></i> Finance
                    </a>
                @endif
                @can('manage products')
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                @endcan
                @can('manage displays')
                    <a href="{{ route('admin.displays.index') }}" class="btn btn-dark">
                        <i class="bi bi-display"></i> Displays
                    </a>
                @endcan
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card admin-stat-card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Users terdaftar</small>
                            <h3 class="mt-2 mb-1">{{ number_format($dashboardStats['total_users'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Total akun di tabel users</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card admin-stat-card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Revenue paid</small>
                            <h3 class="mt-2 mb-1">{{ $money($dashboardStats['paid_revenue']) }}</h3>
                            <span class="text-muted small">Order paid sampai completed</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-cash-coin"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card admin-stat-card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Orders</small>
                            <h3 class="mt-2 mb-1">{{ number_format($dashboardStats['total_orders'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Total pesanan yang tercatat</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-bag-check-fill"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card admin-stat-card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">User aktif</small>
                            <h3 class="mt-2 mb-1">{{ number_format($dashboardStats['active_users'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Login unik 30 hari terakhir</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-activity"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Produk aktif</small>
                            <h3 class="mt-2 mb-1">{{ number_format($dashboardStats['active_products'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Produk yang tampil di katalog</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-box2-heart-fill"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6">
                <div class="card h-100 p-3">
                    <div class="card-body d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <small class="text-muted d-block">Butuh follow up</small>
                            <h3 class="mt-2 mb-1">{{ number_format($dashboardStats['orders_need_follow_up'], 0, ',', '.') }}</h3>
                            <span class="text-muted small">Waiting admin, quoted, pending, challenge</span>
                        </div>
                        <span class="admin-stat-icon">
                            <i class="bi bi-exclamation-circle-fill"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="card p-3 p-lg-4 h-100">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">Order Terbaru</h5>
                            <p class="text-muted mb-0">Pesanan paling baru dari tabel orders.</p>
                        </div>

                        @can('manage orders')
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-arrow-right"></i> Kelola Orders
                            </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentOrders as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->order_code }}</td>
                                        <td>{{ $order->user->name ?? '-' }}</td>
                                        <td><span class="badge bg-dark">{{ str_replace('_', ' ', $order->status) }}</span></td>
                                        <td class="text-end">{{ $money($order->gross_amount) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Status Order</h5>
                    <p class="text-muted mb-3">Jumlah dan nominal order per status.</p>

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
                                @forelse ($ordersByStatus as $row)
                                    <tr>
                                        <td><span class="badge bg-dark">{{ $row['label'] }}</span></td>
                                        <td class="text-end">{{ number_format($row['count'], 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $money($row['amount']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada status order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-5">
                <div class="card p-3 p-lg-4 h-100">
                    <h5 class="mb-1">Produk Teratas</h5>
                    <p class="text-muted mb-3">Produk dengan revenue terbesar dari order paid flow.</p>

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
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada produk terjual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-7">
                <div class="card p-3 p-lg-4 h-100">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                        <div>
                            <h5 class="mb-1">User Terbaru</h5>
                            <p class="text-muted mb-0">Akun terbaru yang ada di sistem admin.</p>
                        </div>

                        @can('manage users')
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-arrow-right"></i> Kelola Users
                            </a>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allUser as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->first()->name ?? 'No Role' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada user.</td>
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
