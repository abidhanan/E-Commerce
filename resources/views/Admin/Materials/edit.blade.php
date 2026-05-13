@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Edit Material</h4>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form action="{{ route('admin.materials.update', $material->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- MATERIAL NAME --}}
                    <div class="mb-3">
                        <label class="form-label">Material Name</label>
                        <input type="text" name="material" class="form-control @error('material') is-invalid @enderror"
                            value="{{ old('material', $material->material) }}" placeholder="e.g. Polyester, Cotton">

                        @error('material')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CURRENT IMAGE --}}
                    @if ($material->image)
                        <div class="mb-3">
                            <label class="form-label">Current Image</label><br>
                            <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->material }}"
                                width="120" class="rounded border">
                        </div>
                    @endif

                    {{-- IMAGE --}}
                    <div class="mb-3">
                        <label class="form-label">Change Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION (TRIX) --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <input id="description" type="hidden" name="description"
                            value="{{ old('description', $material->description) }}">

                        <trix-editor input="description"
                            class="@error('description') border border-danger @enderror"></trix-editor>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Kembali</a>

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
            e.preventDefault();
        });
    </script>
@endpush
