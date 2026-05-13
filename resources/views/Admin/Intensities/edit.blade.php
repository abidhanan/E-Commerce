@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">
        <h4 class="fw-semibold mb-4">Edit Intensities</h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.intensities.update', $intensity->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- INTENSITY --}}
                    <div class="mb-3">
                        <label for="label" class="form-label">Intensities</label>
                        <input type="text" name="label" id="label"
                            class="form-control @error('label') is-invalid @enderror" placeholder="Masukkan intensities..."
                            value="{{ old('label', $intensity->label) }}" readonly>

                        @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION (TRIX) --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <input id="description" type="hidden" name="description"
                            value="{{ old('description', $intensity->description) }}">

                        <trix-editor input="description"
                            class="@error('description') border border-danger @enderror"></trix-editor>

                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- ACTION --}}
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.intensities.index') }}" class="btn btn-secondary">Kembali</a>
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
