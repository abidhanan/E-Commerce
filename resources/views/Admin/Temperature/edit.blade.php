@extends('Admin.Template.index')

@section('title', 'Edit Temperature')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
@endpush

@section('content')
    <div class="container mt-4">

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Edit Temperature</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.temperatures.update', $temperature->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Min Temperature --}}
                    <div class="mb-3">
                        <label class="form-label">Minimal Temperature (°C)</label>
                        <input type="number" name="min_temperature"
                            class="form-control @error('min_temperature') is-invalid @enderror"
                            value="{{ old('min_temperature', $temperature->min_temperature) }}" required>

                        @error('min_temperature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Max Temperature --}}
                    <div class="mb-3">
                        <label class="form-label">Maximal Temperature (°C)</label>
                        <input type="number" name="max_temperature"
                            class="form-control @error('max_temperature') is-invalid @enderror"
                            value="{{ old('max_temperature', $temperature->max_temperature) }}">

                        <small class="text-muted">Leave empty if no upper limit</small>

                        @error('max_temperature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <input id="description" type="hidden" name="description"
                            value="{{ old('description', $temperature->description) }}">

                        <trix-editor input="description"
                            class="@error('description') border border-danger @enderror"></trix-editor>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.temperatures.index') }}" class="btn btn-light">
                            ← Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Data
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>

    <script>
        document.addEventListener("trix-file-accept", function(e) {
            e.preventDefault();
        });
    </script>
@endpush
