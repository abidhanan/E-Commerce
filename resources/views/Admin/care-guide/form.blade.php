@extends('Admin.Template.index')
@section('title', 'Product Care Guide Form')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">
            {{ isset($guide) ? 'Edit Product Care Guide' : 'Add Product Care Guide' }}
        </h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form
                    action="{{ isset($guide) ? route('admin.care-guides.update', $guide->id) : route('admin.care-guides.store') }}"
                    method="POST">
                    @csrf

                    @if (isset($guide))
                        @method('PUT')
                    @endif

                    {{-- Guide Title --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Guide Title
                        </label>

                        <input type="text" name="question" class="form-control"
                            placeholder="Example: How to Wash Your Product"
                            value="{{ old('question', $guide->question ?? '') }}" required>
                    </div>

                    {{-- Guide Content --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Guide Instructions
                        </label>

                        <textarea name="answer" class="form-control" rows="6"
                            placeholder="Example: Use cold water and avoid bleach to maintain product quality." required>{{ old('answer', $guide->answer ?? '') }}</textarea>
                    </div>

                    {{-- Active Status --}}
                    <div class="form-check mb-4">
                        <input type="checkbox" name="is_active" class="form-check-input" value="1"
                            {{ old('is_active', isset($guide) ? (int) $guide->is_active : 1) ? 'checked' : '' }}>

                        <label class="form-check-label">
                            Publish this guide
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-dark">
                            {{ isset($guide) ? 'Update Guide' : 'Save Guide' }}
                        </button>

                        <a href="{{ route('admin.care-guides.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
