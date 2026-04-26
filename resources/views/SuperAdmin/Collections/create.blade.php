@extends('SuperAdmin.Template.index')
@section('content')
    <div class="container mt-4">

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add Collections</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.collections.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('superadmin.collections.index') }}" class="btn btn-secondary">
                            Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection
