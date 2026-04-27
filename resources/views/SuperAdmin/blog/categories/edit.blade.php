@extends('SuperAdmin.Template.Index')

@section('title', 'Edit Blog Category')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>Edit Blog Category</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('superadmin.blog-categories.update', $blog_category->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- NAME --}}
                <div class="mb-3">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $blog_category->name) }}" required>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- SLUG (READ ONLY / OPTIONAL) --}}
                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" class="form-control" value="{{ $blog_category->slug }}" readonly>
                    <small class="text-muted">Slug otomatis dari nama</small>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>

                    <a href="{{ route('superadmin.blog-categories.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection
