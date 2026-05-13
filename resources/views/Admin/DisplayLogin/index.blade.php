@extends('Admin.Template.index')
@section('title', 'Login Display Management')
@push('css')
    <style>
        img {
            max-width: 100px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Login Display Management',
            'createRoute' => route('admin.display-logins.create'),
            'createLabel' => '+ Tambah Banner Login',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'displayLoginInfoCollapse',
            'helpTitle' => 'Keterangan Display Login',
            'messages' => [
                'Halaman ini mengelola banner atau visual yang ditampilkan pada halaman login pengguna.',
                'Tambahkan atau ubah banner untuk kebutuhan promosi, informasi, atau penguatan tampilan halaman login.',
                'Urutan banner dapat diatur dengan drag & drop dan akan memengaruhi urutan tampil di sisi pengguna.',
            ],
        ])

        <p class="text-muted mb-4">
            Kelola banner atau visual yang ditampilkan pada halaman login pengguna.
            Anda dapat mengatur urutan tampilan dengan drag & drop.
        </p>

        {{-- Card --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                @if ($displayLogins->count() > 0)
                    <ul id="faq-list" class="list-group list-group-flush">

                        @foreach ($displayLogins as $displayLogin)
                            <li class="list-group-item d-flex justify-content-between align-items-center gap-3 py-3"
                                data-id="{{ $displayLogin->id }}">

                                {{-- Left Content --}}
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-1">
                                        {{ $displayLogin->label }}
                                    </strong>

                                    <small class="text-muted">
                                        File:
                                        <img src="{{ asset('storage/' . $displayLogin->image_path) }}" alt="">

                                    </small>
                                </div>

                                {{-- Right Actions --}}
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                    <a href="{{ route('admin.display-logins.edit', $displayLogin->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Ubah
                                    </a>

                                    <form action="{{ route('admin.display-logins.destroy', $displayLogin->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus banner login ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash me-1"></i>
                                            Hapus Banner
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <i class="bi bi-image text-muted" style="font-size: 48px;"></i>

                        <h5 class="mt-3 mb-2">
                            Belum Ada Banner Login
                        </h5>

                        <p class="text-muted mb-3">
                            Tambahkan banner pertama untuk ditampilkan pada halaman login pengguna.
                        </p>

                        <a href="{{ route('admin.display-logins.create') }}" class="btn btn-primary">
                            Tambah Banner Sekarang
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        const listElement = document.getElementById('faq-list');

        if (listElement) {
            new Sortable(listElement, {
                animation: 150,

                onEnd: function() {
                    let order = [];

                    document.querySelectorAll('#faq-list li').forEach((el) => {
                        order.push(el.dataset.id);
                    });

                    fetch("{{ route('admin.display-logins.reorder') }}", {
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
        }
    </script>
@endpush
