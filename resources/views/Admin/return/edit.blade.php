@extends('Admin.Template.index')

@section('title', 'Edit Return Step')

@push('css')
@endpush

@section('content')
    <div class="container-fluid">

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Edit Return Step</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.return-steps.update', $step->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Step Title --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Step Title</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $step->title) }}" placeholder="Example: Payment" required>

                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $step->slug) }}"
                                placeholder="payment">

                            @error('slug')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Step Order --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Step Order</label>
                            <input type="number" name="step_order" class="form-control"
                                value="{{ old('step_order', $step->step_order) }}" min="1" required>

                            @error('step_order')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Optional description">{{ old('description', $step->description) }}</textarea>

                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">Status</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="1"
                                    {{ old('is_active', $step->is_active) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="0"
                                    {{ old('is_active', $step->is_active) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label">Inactive</label>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.return-steps') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Update Step
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelector('[name="title"]').addEventListener('keyup', function() {
            let slug = this.value
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '');

            document.querySelector('[name="slug"]').value = slug;
        });
    </script>
@endpush
