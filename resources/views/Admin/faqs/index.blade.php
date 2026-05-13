@extends('Admin.Template.index')
@section('title', 'FAQ')

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'FAQ',
            'createRoute' => route('admin.faqs.create'),
            'createLabel' => '+ Tambah FAQ',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'faqInfoCollapse',
            'helpTitle' => 'Keterangan FAQ',
            'messages' => [
                'Halaman ini berisi daftar pertanyaan umum dan jawaban yang akan ditampilkan kepada pelanggan.',
                'Gunakan status aktif untuk menentukan FAQ yang tampil, dan perbarui jawabannya agar informasi tetap relevan.',
                'Urutan FAQ dapat diatur dengan drag & drop agar pertanyaan yang paling penting muncul lebih dulu.',
            ],
        ])

        <div class="card">
            <div class="card-body">

                <ul id="faq-list" class="list-group">
                    @foreach ($faqs as $faq)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-3"
                            data-id="{{ $faq->id }}">

                            <div class="flex-grow-1">
                                <strong>{{ $faq->question }}</strong>
                                <br>
                                <small>{{ Str::limit($faq->answer, 80) }}</small>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                <form action="{{ route('admin.faqs.toggle-status', $faq->id) }}" method="POST"
                                    class="d-flex align-items-center gap-2 mb-0">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            id="faq-active-{{ $faq->id }}" {{ $faq->is_active ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                    </div>

                                    <label class="mb-0 small fw-semibold" for="faq-active-{{ $faq->id }}">
                                        {{ $faq->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </label>
                                </form>

                                <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
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
        new Sortable(document.getElementById('faq-list'), {
            animation: 150,
            onEnd: function() {
                let order = [];

                document.querySelectorAll('#faq-list li').forEach((el) => {
                    order.push(el.dataset.id);
                });

                fetch("{{ route('admin.faqs.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: order
                    })
                });
            }
        });
    </script>
@endpush
