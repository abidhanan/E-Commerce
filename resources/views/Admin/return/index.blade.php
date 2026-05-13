@extends('Admin.Template.index')

@section('title', 'Return Steps')

@push('css')
    <style>
        .badge-active {
            background: #198754;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-inactive {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Return Progress Steps',
            'createRoute' => route('admin.return-steps.create'),
            'createLabel' => '+ Add Step',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'returnInfoCollapse',
            'helpTitle' => 'Keterangan Return',
            'messages' => [
                'Halaman ini mengelola langkah-langkah retur yang akan dilihat pelanggan saat membutuhkan panduan pengembalian barang.',
                'Nilai order mengatur urutan proses retur, dan status aktif menentukan step mana yang ditampilkan di halaman pelanggan.',
                'Pastikan setiap langkah menjelaskan prosedur retur dengan jelas agar alurnya mudah diikuti pengguna.',
            ],
        ])

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($steps->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Title</th>

                                    <th>Description</th>
                                    <th width="10%">Order</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($steps as $step)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $step->title }}</td>
                                        <td>
                                            {{ $step->description ?? '-' }}
                                        </td>
                                        <td>{{ $step->step_order }}</td>
                                        <td>
                                            @if ($step->is_active)
                                                <span class="badge-active">Active</span>
                                            @else
                                                <span class="badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.return-steps.edit', $step->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.return-steps.destroy', $step->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete this step?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <h6>No return steps found</h6>
                        <p class="text-muted">Please add a new step.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
@endpush
