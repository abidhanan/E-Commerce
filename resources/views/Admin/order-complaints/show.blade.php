@extends('Admin.Template.index')

@section('title', 'Komplain ' . $complaint->order?->order_code)

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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Komplain {{ $complaint->order->order_code ?? '-' }}</h4>
                <div class="text-muted">{{ $complaint->created_at->format('d M Y H:i') }}</div>
            </div>
            <a href="{{ route('admin.order-complaints.index') }}" class="btn btn-outline-dark">Kembali</a>
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
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                            <div>
                                <h5 class="mb-1">{{ $complaint->subject }}</h5>
                                <div class="text-muted">{{ $complaint->user->name ?? '-' }} / {{ $complaint->user->email ?? '-' }}</div>
                            </div>
                            <span class="badge {{ $complaint->status === 'resolved' ? 'bg-success' : ($complaint->status === 'rejected' ? 'bg-danger' : 'bg-dark') }}">
                                {{ $statusLabels[$complaint->status] ?? $complaint->status }}
                            </span>
                        </div>
                        <p class="mb-0" style="white-space: pre-line;">{{ $complaint->message }}</p>

                        @if ($complaint->photos->isNotEmpty())
                            <hr>
                            <div class="row g-3">
                                @foreach ($complaint->photos as $photo)
                                    <div class="col-6 col-md-4">
                                        <a href="{{ asset('storage/' . $photo->path) }}" target="_blank" rel="noopener">
                                            <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto komplain"
                                                class="img-fluid rounded border">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Order & Customer</h5>
                        <p class="mb-1"><strong>{{ $complaint->order->user->name ?? $complaint->user->name ?? '-' }}</strong></p>
                        <p class="text-muted mb-3">{{ $complaint->order->user->email ?? $complaint->user->email ?? '-' }}</p>

                        @if ($complaint->order?->address)
                            <p class="mb-1">
                                {{ $complaint->order->address->recipient_name }} /
                                {{ $complaint->order->address->phone_number }}
                            </p>
                            <p class="text-muted mb-3">
                                {{ $complaint->order->address->full_address }},
                                {{ $complaint->order->address->city }},
                                {{ $complaint->order->address->province }}
                                {{ $complaint->order->address->postal_code }}
                            </p>
                        @endif

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaint->order->items ?? [] as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? '-' }}</td>
                                            <td>{{ $item->productVariant->size ?? '-' }}</td>
                                            <td>{{ $item->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($complaint->order)
                            <a href="{{ route('admin.orders.show', $complaint->order) }}" class="btn btn-outline-dark">
                                Buka Detail Order
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Proses Komplain</h5>
                        <form action="{{ route('admin.order-complaints.update', $complaint) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach ($statusLabels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $complaint->status) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Respons Admin</label>
                                <textarea name="admin_response" class="form-control" rows="5"
                                    placeholder="Tulis hasil pengecekan atau instruksi untuk customer">{{ old('admin_response', $complaint->admin_response) }}</textarea>
                            </div>

                            @if ($complaint->resolved_at)
                                <p class="text-muted">Selesai pada {{ $complaint->resolved_at->format('d M Y H:i') }}</p>
                            @endif

                            <button class="btn btn-dark w-100">Simpan Proses</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
