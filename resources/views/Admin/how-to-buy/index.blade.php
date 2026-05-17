@extends('Admin.Template.index')

@section('title', 'How To Buy Steps')

@push('css')
    <style>
        .badge-active {
            background: var(--gold);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge-inactive {
            background: var(--dark);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'How To Buy Progress Steps',
            'createRoute' => route('admin.how-to-buy-steps.create'),
            'createLabel' => '+ Add Step',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'howToBuyInfoCollapse',
            'helpTitle' => 'Keterangan How To Buy',
            'messages' => [
                'Halaman ini mengelola langkah-langkah pembelian yang ditampilkan kepada pelanggan sebagai panduan proses belanja.',
                'Nilai order menentukan urutan step, sedangkan status aktif menentukan apakah langkah tersebut ditampilkan atau disembunyikan.',
                'Gunakan setiap step untuk menjelaskan alur pembelian dari pemilihan produk sampai penyelesaian pesanan.',
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
                                    <th>Step Title</th>
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

                                        <td>
                                            <strong>{{ $step->title }}</strong>
                                        </td>

                                        <td>
                                            {{ $step->description ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $step->step_order }}
                                        </td>

                                        <td>
                                            @if ($step->is_active)
                                                <span class="badge-active">
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge-inactive">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <form action="{{ route('admin.how-to-buy.toggle-status', $step->id) }}" method="POST"
                                                class="d-flex align-items-center gap-2 mb-0">
                                                @csrf
                                                @method('PATCH')
            
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="step-active-{{ $step->id }}" {{ $step->is_active ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                </div>
            
                                                <label class="mb-0 small fw-semibold" for="step-active-{{ $step->id }}">
                                                    {{ $step->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </label>
                                            </form>
                                            <a href="{{ route('admin.how-to-buy-steps.edit', $step->id) }}"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.how-to-buy-steps.destroy', $step->id) }}"
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
                        <h6>No purchase steps found</h6>
                        <p class="text-muted">
                            Start by adding your first "How To Buy" step.
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('scripts')
@endpush
