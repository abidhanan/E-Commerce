@extends('Admin.Template.index')

@section('title', 'Komplain Order')

@section('content')
    @php
        $statusLabels = [
            'submitted' => 'Baru',
            'in_review' => 'Diproses',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
        ];
    @endphp

    <div class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Komplain Order</h4>
                <div class="text-muted">Laporan dari user terkait pesanan yang sedang dikirim atau selesai.</div>
            </div>
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statusLabels as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-dark">Filter</button>
            </form>
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
                                <th>Komplain</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($complaints as $complaint)
                                <tr>
                                    <td class="fw-semibold">{{ $complaint->order->order_code ?? '-' }}</td>
                                    <td>{{ $complaint->user->name ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $complaint->subject }}</div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($complaint->message, 90) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $complaint->status === 'resolved' ? 'bg-success' : ($complaint->status === 'rejected' ? 'bg-danger' : 'bg-dark') }}">
                                            {{ $statusLabels[$complaint->status] ?? $complaint->status }}
                                        </span>
                                    </td>
                                    <td>{{ $complaint->photos->count() }}</td>
                                    <td>{{ $complaint->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.order-complaints.show', $complaint) }}" class="btn btn-sm btn-dark">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada komplain.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
