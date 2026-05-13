@extends('Admin.Template.index')
@section('title', 'Crash Replacement')

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Crash Replacement',
            'createRoute' => route('admin.crash-replacements.create'),
            'createLabel' => '+ Tambah Crash Replacement',
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

                <ul id="crash-replacements-list" class="list-group">
                    @foreach ($crashReplacements as $crashReplacement)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-3"
                            data-id="{{ $crashReplacement->id }}">

                            <div class="flex-grow-1">
                                <strong>{{ $crashReplacement->question }}</strong>
                                <br>
                                <small>{{ Str::limit($crashReplacement->answer, 80) }}</small>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                <form action="{{ route('admin.crash-replacements.toggle-status', $crashReplacement->id) }}"
                                    method="POST" class="d-flex align-items-center gap-2 mb-0">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox"
                                            id="crash-replacement-active-{{ $crashReplacement->id }}"
                                            {{ $crashReplacement->is_active ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                    </div>

                                    <label class="mb-0 small fw-semibold"
                                        for="crash-replacement-active-{{ $crashReplacement->id }}">
                                        {{ $crashReplacement->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </label>
                                </form>

                                <a href="{{ route('admin.crash-replacements.edit', $crashReplacement->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.crash-replacements.destroy', $crashReplacement->id) }}"
                                    method="POST">
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
        const sortableElement = document.getElementById('crash-replacements-list');

        console.log('Sortable Element:', sortableElement);

        if (!sortableElement) {
            console.error('Element #crash-replacements-list tidak ditemukan!');
        } else {
            new Sortable(sortableElement, {
                animation: 150,

                onEnd: function(evt) {
                    console.log('Drag selesai');
                    console.log('Old Index:', evt.oldIndex);
                    console.log('New Index:', evt.newIndex);

                    let order = [];

                    // FIX selector (samakan dengan id sortable)
                    document.querySelectorAll('#crash-replacements-list li').forEach((el) => {
                        console.log('Item ditemukan:', el);
                        console.log('Data ID:', el.dataset.id);

                        order.push(el.dataset.id);
                    });



                    fetch("{{ route('admin.crash-replacements.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order: order
                            })
                        })
                        .then(response => {
                            console.log('Raw Response:', response);

                            if (!response.ok) {
                                throw new Error(
                                    `HTTP Error: ${response.status} - ${response.statusText}`
                                );
                            }

                            return response.json();
                        })
                        .then(data => {
                            console.log('Success Response:', data);
                        })
                        .catch(error => {
                            console.error('Fetch Error:', error);
                        });
                }
            });
        }
    </script>
@endpush
