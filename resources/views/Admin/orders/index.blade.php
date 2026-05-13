@extends('Admin.Template.index')

@section('title', 'Orders')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Pesanan</h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->order_code }}</td>
                                    <td>{{ $order->user->name ?? '-' }}</td>
                                    <td>{{ $order->items->sum('qty') }}</td>
                                    <td><span class="badge bg-dark">{{ str_replace('_', ' ', $order->status) }}</span></td>
                                    <td>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-dark">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
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
    </div>
@endsection
