@extends('Admin.Template.index')

@section('title', 'Consent Documents')

@section('content')
    <div class="container-fluid">
        @include('Admin.partials.index-help-header', [
            'heading' => 'Consent Documents',
            'createRoute' => route('admin.consent-documents.create'),
            'createLabel' => '+ Add Document',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'consentInfoCollapse',
            'helpTitle' => 'Keterangan Consent',
            'messages' => [
                'Halaman ini mengelola isi Terms, Privacy Policy, dan Newsletter Offers yang ditampilkan dari checkbox register.',
                'Slug aktif digunakan untuk halaman publik. Default register memakai terms-privacy dan newsletter-offers.',
                'Nonaktifkan dokumen jika tidak ingin halaman tersebut bisa dibuka user.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th class="text-end">Position</th>
                                <th class="text-center" width="210">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $document)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $document->title }}</div>
                                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($document->summary, 90) }}</small>
                                    </td>
                                    <td><span class="badge text-bg-light border">{{ $document->type_label }}</span></td>
                                    <td>
                                        <a href="{{ route('legal.show', $document->slug) }}" target="_blank"
                                            class="text-decoration-none">
                                            {{ $document->slug }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $document->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $document->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-end">{{ $document->position }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.consent-documents.edit', $document) }}"
                                                class="btn btn-sm btn-warning">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.consent-documents.destroy', $document) }}"
                                                method="POST" class="m-0" data-confirm-message="Hapus dokumen ini?"
                                                data-confirm-title="Hapus Consent Document" data-confirm-button="Hapus"
                                                data-confirm-class="btn-danger">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada consent document.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $documents->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
