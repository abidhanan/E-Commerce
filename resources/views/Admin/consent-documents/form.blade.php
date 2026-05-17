@extends('Admin.Template.index')

@section('title', $document->exists ? 'Edit Consent Document' : 'Add Consent Document')

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Consent</span>
                <h1 class="admin-page-title">{{ $document->exists ? 'Edit Consent Document' : 'Add Consent Document' }}</h1>
                <p class="admin-page-subtitle">Kelola konten yang dibaca user dari checkbox register.</p>
            </div>
            <div class="admin-page-actions">
                <a href="{{ route('admin.consent-documents.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form
                    action="{{ $document->exists ? route('admin.consent-documents.update', $document) : route('admin.consent-documents.store') }}"
                    method="POST">
                    @csrf
                    @if ($document->exists)
                        @method('PUT')
                    @endif

                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required
                                    value="{{ old('title', $document->title) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Summary</label>
                                <textarea name="summary" class="form-control" rows="3">{{ old('summary', $document->summary) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="12" required>{{ old('content', $document->content) }}</textarea>
                                <small class="text-muted">Gunakan baris kosong untuk memisahkan paragraf.</small>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-select" required>
                                            @foreach ($typeOptions as $value => $label)
                                                <option value="{{ $value }}" @selected(old('type', $document->type) === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug</label>
                                        <input type="text" name="slug" class="form-control"
                                            value="{{ old('slug', $document->slug) }}" placeholder="terms-privacy">
                                        <small class="text-muted">Kosongkan untuk generate dari title.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Position</label>
                                        <input type="number" name="position" class="form-control" min="0"
                                            value="{{ old('position', $document->position ?? 0) }}">
                                    </div>

                                    <div class="form-check form-switch mb-4">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            id="is_active" @checked(old('is_active', $document->is_active ?? true))>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ $document->exists ? 'Update Document' : 'Create Document' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
