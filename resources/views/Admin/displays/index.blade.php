@extends('Admin.Template.index')
@section('title', 'Displays')

@section('content')
    <div class="container-fluid">
        @include('Admin.partials.index-help-header', [
            'heading' => 'Displays',
            'createRoute' => $displays->count() === 0 ? route('admin.displays.create') : null,
            'createLabel' => '+ Tambah Display',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'displaysInfoCollapse',
            'helpTitle' => 'Keterangan Displays',
            'messages' => [
                'Halaman ini digunakan untuk mengatur tampilan utama homepage seperti hero image dan running text.',
                'Data yang disimpan di sini akan memengaruhi banner utama storefront, termasuk judul, subjudul, teks berjalan, dan tautan kampanye.',
                'Gunakan sakelar tayang di halaman edit untuk mematikan/menghidupkan kampanye kolaborasi (Hero 2).',
            ],
        ])

        <p class="text-muted mb-4">Kelola hero image, running text, best seller, dan custom collection untuk tampilan utama storefront.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card p-3 p-lg-4 border-0 shadow-sm">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <div>
                    <h5 class="mb-1">Daftar Display</h5>
                    <p class="text-muted mb-0">Biasanya cukup satu record aktif untuk homepage. Edit record yang ada untuk mengubah tampilan.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.bestsellers.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-stars"></i> Best Seller
                    </a>
                    <a href="{{ route('admin.custom-collections-display.index') }}" class="btn btn-dark">
                        <i class="bi bi-grid-1x2"></i> Custom Collections
                    </a>
                    <a href="{{ route('admin.social-links.index') }}" class="btn btn-outline-dark">
                        <i class="bi bi-share"></i> Social & Stores
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-center">
                        <tr>
                            <th width="60">No</th>
                            <th>Hero 1</th>
                            <th>Hero 2 (Collab)</th>
                            <th>Hero 3</th>
                            <th>Running Text</th>
                            <th width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($displays as $index => $display)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                
                                {{-- HERO 1 --}}
                                <td class="text-center">
                                    @if ($display->image_1_path)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $display->image_1_path) }}" width="100" class="mb-2 rounded-3 border">
                                            <span class="badge {{ $display->image_1_is_active ? 'bg-success' : 'bg-danger' }} position-absolute top-0 start-100 translate-middle" style="font-size: 10px;">
                                                {{ $display->image_1_is_active ? 'ON' : 'OFF' }}
                                            </span>
                                        </div>
                                        <div class="mt-1"><strong>{{ $display->image_1_title }}</strong></div>
                                        <div class="text-muted small">{{ $display->image_1_sub_title }}</div>
                                        @if($display->image_1_link)
                                            <a href="{{ $display->image_1_link }}" target="_blank" class="badge text-bg-light border text-decoration-none mt-1" title="{{ $display->image_1_link }}">
                                                <i class="bi bi-link-45deg"></i> Tertaut
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- HERO 2 (KOLABORASI) --}}
                                <td class="text-center">
                                    @if ($display->image_2_path)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $display->image_2_path) }}" width="100" class="mb-2 rounded-3 border">
                                            <span class="badge {{ $display->image_2_is_active ? 'bg-success' : 'bg-danger' }} position-absolute top-0 start-100 translate-middle" style="font-size: 10px;">
                                                {{ $display->image_2_is_active ? 'ON' : 'OFF' }}
                                            </span>
                                        </div>
                                        <div class="mt-1"><strong>{{ $display->image_2_title }}</strong></div>
                                        <div class="text-muted small">{{ $display->image_2_sub_title }}</div>
                                        @if($display->image_2_link)
                                            <a href="{{ $display->image_2_link }}" target="_blank" class="badge text-bg-light border text-decoration-none mt-1" title="{{ $display->image_2_link }}">
                                                <i class="bi bi-link-45deg"></i> Tertaut
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- HERO 3 --}}
                                <td class="text-center">
                                    @if ($display->image_3_path)
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ asset('storage/' . $display->image_3_path) }}" width="100" class="mb-2 rounded-3 border">
                                            <span class="badge {{ $display->image_3_is_active ? 'bg-success' : 'bg-danger' }} position-absolute top-0 start-100 translate-middle" style="font-size: 10px;">
                                                {{ $display->image_3_is_active ? 'ON' : 'OFF' }}
                                            </span>
                                        </div>
                                        <div class="mt-1"><strong>{{ $display->image_3_title }}</strong></div>
                                        <div class="text-muted small">{{ $display->image_3_sub_title }}</div>
                                        @if($display->image_3_link)
                                            <a href="{{ $display->image_3_link }}" target="_blank" class="badge text-bg-light border text-decoration-none mt-1" title="{{ $display->image_3_link }}">
                                                <i class="bi bi-link-45deg"></i> Tertaut
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>{{ \Illuminate\Support\Str::limit($display->running_text, 100) }}</td>
                                
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        <a href="{{ route('admin.displays.edit', $display->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.displays.destroy', $display->id) }}" method="POST"
                                            class="d-inline"
                                            data-confirm-message="Yakin ingin menghapus konfigurasi display ini?"
                                            data-confirm-title="Hapus Display" 
                                            data-confirm-button="Hapus" 
                                            data-confirm-class="btn-danger">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data display.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection