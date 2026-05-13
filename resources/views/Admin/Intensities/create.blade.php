@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">
        <h4 class="fw-semibold mb-4">Tambah Intensities</h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.intensities.store') }}" method="POST">
                    @csrf

                    {{-- INTENSITY --}}
                    <div class="mb-3">
                        <label for="label" class="form-label">Intensities</label>
                        <select name="label" id="label" class="form-control @error('label') is-invalid @enderror">
                            <option value="">Pilih Intensities</option>
                            <option value="low" {{ old('label') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="high" {{ old('label') == 'high' ? 'selected' : '' }}>High</option>
                        </select>

                        @error('label')
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
