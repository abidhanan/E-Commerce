@extends('Admin.Template.index')
@section('title', 'Social and Stores')

@section('content')
    <div class="container-fluid">
        @include('Admin.partials.index-help-header', [
            'heading' => 'Social Media & Official Stores',
            'createRoute' => route('admin.social-links.create'),
            'createLabel' => '+ Tambah Link',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'socialLinksInfoCollapse',
            'helpTitle' => 'Keterangan Social Links',
            'messages' => [
                'Halaman ini mengelola link media sosial dan official store yang dipakai pada storefront, terutama untuk footer atau section terkait.',
                'Type memisahkan data antara social media dan marketplace, sedangkan posisi menentukan urutan tampil link di sisi pelanggan.',
                'Hanya link yang aktif yang akan ditampilkan, jadi pastikan URL dan label setiap platform sudah benar sebelum dipublikasikan.',
            ],
        ])

        <p class="text-muted mb-4">Kelola link sosial media dan link official store. Marketplace yang disiapkan:
            Shopee, Tokopedia, Zalora, dan TikTok Shop.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4 mb-4">
            @foreach ($typeOptions as $type => $label)
                <div class="col-md-6">
                    <div class="card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h5 class="mb-1">{{ $label }}</h5>
                                <p class="text-muted mb-0">
                                    {{ $socialLinks->where('type', $type)->where('is_active', true)->count() }} link aktif
                                </p>
                            </div>
                            <span class="badge text-bg-light border">
                                {{ $socialLinks->where('type', $type)->count() }} total
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card p-3 p-lg-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                <div>
                    <h5 class="mb-1">Daftar Link</h5>
                    <p class="text-muted mb-0">Link aktif akan dipakai di footer storefront. Urutan mengikuti nilai posisi
                        terkecil ke terbesar.</p>
                </div>

                <a href="{{ route('admin.displays.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left"></i> Kembali ke Displays
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Platform</th>
                            <th>Label</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Posisi</th>
                            <th class="text-center" width="180">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($socialLinks as $link)
                            <tr>
                                <td>
                                    <span
                                        class="badge text-bg-light border">{{ $typeOptions[$link->type] ?? ucfirst($link->type) }}</span>
                                </td>
                                <td>{{ \App\Models\SocialLink::labelForPlatform($link->platform) }}</td>
                                <td>{{ $link->display_label }}</td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer">
                                        {{ \Illuminate\Support\Str::limit($link->url, 45) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $link->is_active ? 'bg-success' : 'text-bg-light border' }}">
                                        {{ $link->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>{{ $link->position }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        <a href="{{ route('admin.social-links.edit', $link) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.social-links.destroy', $link) }}" method="POST"
                                            data-confirm-message="Hapus link {{ $link->display_label }}?"
                                            data-confirm-title="Hapus Link" data-confirm-button="Hapus"
                                            data-confirm-class="btn-danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    Belum ada link sosial atau official store.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
