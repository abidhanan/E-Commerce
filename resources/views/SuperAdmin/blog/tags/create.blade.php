@extends('SuperAdmin.Template.Index')

@section('title', 'Create Tag')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>Create Tag</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('superadmin.tags.store') }}" method="POST">
                @csrf

                {{-- NAME --}}
                <div class="mb-3">
                    <label class="form-label">Tag Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>

                    <a href="{{ route('superadmin.tags.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>

@endsection
