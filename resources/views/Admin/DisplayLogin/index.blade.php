@extends('Admin.Template.index')
@section('title', 'Login Display Management')
@push('css')
    <style>
        .banner-preview {
            width: 120px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        .drag-handle {
            cursor: grab;
            color: #adb5bd;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Login Display Management',
            'createRoute' => route('admin.display-logins.create'),
            'createLabel' => '+ Tambah Banner Login',
            'createClass' => 'btn btn-primary btn-sm ' . ($displayLogins->count() >= 5 ? 'disabled' : ''),
            'collapseId' => 'displayLoginInfoCollapse',
            'helpTitle' => 'Keterangan Display Login',
            'messages' => [
                'Halaman ini mengelola banner yang ditampilkan pada halaman login dan register pengguna.',
                'Maksimal hanya 5 banner yang diizinkan untuk menjaga performa loading halaman.',
                'Urutan banner dapat diatur dengan menahan dan menggeser ikon titik (drag & drop).',
                'Gunakan tombol Tampilkan/Sembunyikan untuk mengatur status tanpa harus menghapus gambar.'
            ],
        ])

        {{-- RADAR SLOT: Informasikan admin berapa slot yang tersisa --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">KAPASITAS BANNER</span>
                <span class="badge {{ $displayLogins->count() >= 5 ? 'bg-danger' : 'bg-dark' }}">{{ $displayLogins->count() }} / 5 Slot Terpakai</span>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar {{ $displayLogins->count() >= 5 ? 'bg-danger' : 'bg-dark' }}" role="progressbar" style="width: {{ ($displayLogins->count() / 5) * 100 }}%;"></div>
            </div>
            @if($displayLogins->count() >= 5)
                <small class="text-danger fw-bold mt-1 d-block"><i class="bi bi-exclamation-triangle-fill me-1"></i> Slot penuh. Hapus banner lama untuk menambah yang baru.</small>
            @endif
        </div>

        {{-- ERROR RADAR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Card --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                @if ($displayLogins->count() > 0)
                    <ul id="banner-list" class="list-group list-group-flush">

                        @foreach ($displayLogins as $displayLogin)
                            <li class="list-group-item d-flex justify-content-between align-items-center gap-3 py-3" data-id="{{ $displayLogin->id }}">

                                {{-- Left Content: Drag Handle & Image --}}
                                <div class="d-flex align-items-center gap-3">
                                    <i class="bi bi-grip-vertical drag-handle fs-4"></i>
                                    
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $displayLogin->image_path) }}" class="banner-preview" alt="{{ $displayLogin->label }}">
                                        @if(!$displayLogin->is_active)
                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center" style="border-radius: 6px;">
                                                <span class="badge bg-secondary">HIDDEN</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <strong class="d-block mb-1 text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                                            {{ $displayLogin->label }}
                                        </strong>
                                        @if($displayLogin->is_active)
                                            <span class="badge bg-success rounded-pill" style="font-size: 0.7rem;">Tayang di Frontend</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">Disembunyikan</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right Actions: Toggle, Edit, Delete --}}
                                <div class="d-flex align-items-center gap-2 flex-shrink-0">

                                    {{-- SAKELAR MUTLAK: Tampilkan / Sembunyikan --}}
                                    <form action="{{ route('admin.display-logins.toggle-status', $displayLogin->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $displayLogin->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $displayLogin->is_active ? 'Sembunyikan Banner' : 'Tampilkan Banner' }}">
                                            <i class="bi {{ $displayLogin->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.display-logins.edit', $displayLogin->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form action="{{ route('admin.display-logins.destroy', $displayLogin->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus banner login ini secara permanen?')" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <i class="bi bi-images text-muted" style="font-size: 48px;"></i>
                        <h5 class="mt-3 mb-2 font-weight-light">Belum Ada Banner Login</h5>
                        <p class="text-muted mb-4">Maksimal 5 banner dapat ditambahkan untuk memperkuat visual halaman otentikasi.</p>
                        <a href="{{ route('admin.display-logins.create') }}" class="btn btn-dark text-uppercase fw-bold" style="font-size: 0.85rem; letter-spacing: 1px;">
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
        const listElement = document.getElementById('banner-list');

        if (listElement) {
            new Sortable(listElement, {
                animation: 150,
                handle: '.drag-handle', // Membatasi area drag hanya pada ikon titik-titik agar tidak mengganggu klik tombol
                ghostClass: 'bg-light',
                onEnd: function() {
                    let order = [];
                    document.querySelectorAll('#banner-list li').forEach((el) => {
                        order.push(el.dataset.id);
                    });

                    fetch("{{ route('admin.display-logins.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ order: order })
                    }).then(response => {
                        if(!response.ok) {
                            alert('Gagal menyimpan urutan. Silakan muat ulang halaman.');
                        }
                    });
                }
            });
        }
    </script>
@endpush