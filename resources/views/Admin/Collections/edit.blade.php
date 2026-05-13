@extends('Admin.Template.index')
@section('content')
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Collections</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.collections.update', $collections->id) }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $collections->name) }}" required>
                    </div>

                    <div class="mb-3">

                        <label class="form-label">Image</label>

                        @if ($collections->img)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $collections->img) }}"
                                    style="height:80px;border-radius:6px;">
                            </div>
                        @endif

                        <input type="file" name="img" class="form-control" accept="image/*">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti gambar
                        </small>

                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('admin.collections.index') }}" class="btn btn-secondary">
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
