@extends('Admin.Template.index')
@section('title', 'Product Care Guide')

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Product Care Guide',
            'createRoute' => route('admin.care-guides.create'),
            'createLabel' => '+ Add Guide',
            'createClass' => 'btn btn-dark btn-sm',
            'collapseId' => 'careGuideInfoCollapse',
            'helpTitle' => 'Keterangan Product Care Guide',
            'messages' => [
                'Halaman ini mengelola panduan perawatan produk yang ditampilkan ke pelanggan, seperti cara cuci, penyimpanan, dan perawatan khusus.',
                'Gunakan judul pertanyaan sebagai ringkasan topik panduan, lalu isi jawaban dengan instruksi perawatan yang jelas dan mudah dipahami.',
                'Status aktif menentukan apakah panduan tampil, dan drag & drop dipakai untuk mengatur urutan tampil di halaman pelanggan.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="mb-3">
                    <small class="text-muted">
                        Manage product care instructions shown to customers such as washing instructions,
                        storage tips, maintenance guides, and warranty care.
                    </small>
                </div>

                <ul id="guide-list" class="list-group">
                    @foreach ($guides as $guide)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-3"
                            data-id="{{ $guide->id }}">

                            <div class="flex-grow-1">
                                <strong>{{ $guide->question }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($guide->answer, 100) }}
                                </small>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-shrink-0">

                                {{-- Toggle Status --}}
                                <form action="{{ route('admin.care-guides.toggle-status', $guide->id) }}" method="POST"
                                    class="d-flex align-items-center gap-2 mb-0">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            id="guide-active-{{ $guide->id }}" {{ $guide->is_active ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                    </div>

                                    <label class="mb-0 small fw-semibold" for="guide-active-{{ $guide->id }}">
                                        {{ $guide->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </form>

                                {{-- Edit --}}
                                <a href="{{ route('admin.care-guides.edit', $guide->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.care-guides.destroy', $guide->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        new Sortable(document.getElementById('guide-list'), {
            animation: 150,

            onEnd: function() {
                let order = [];

                document.querySelectorAll('#guide-list li').forEach((el) => {
                    order.push(el.dataset.id);
                });

                console.log("Dragged Order:", order);

                fetch("{{ route('admin.care-guides.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            order: order
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log("Backend Response:", data);

                        console.table(data.updated_order);
                    })
                    .catch(err => {
                        console.error("Error:", err);
                    });
            }
        });
    </script>
@endpush
