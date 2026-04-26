@extends('SuperAdmin.Template.index')
@section('content')
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('superadmin.categories.update', $blog_category->id) }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $blog_category->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- None --</option>

                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('parent_id', $blog_category->parent_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">

                        <label class="form-label">Image</label>

                        @if ($blog_category->img)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $blog_category->img) }}"
                                    style="height:80px;border-radius:6px;">
                            </div>
                        @endif

                        <input type="file" name="img" class="form-control" accept="image/*">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti gambar
                        </small>

                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
