@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Tambah Breathability</h4>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.breathabilities.store') }}" method="POST">
                    @csrf

                    {{-- LEVEL --}}
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="number" class="form-control @error('level') is-invalid @enderror" id="level"
                            name="level" value="{{ old('level') }}" required>

                        @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION (TRIX) --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <input id="description" type="hidden" name="description" value="{{ old('description') }}">

                        <trix-editor input="description"
                            class="@error('description') border border-danger @enderror"></trix-editor>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.breathabilities.index') }}" class="btn btn-secondary">Kembali</a>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
@endpush

@push('scripts')
    <script src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>

    <script>
        document.addEventListener("trix-file-accept", function(e) {
            e.preventDefault(); // disable upload
        });
    </script>
@endpush
