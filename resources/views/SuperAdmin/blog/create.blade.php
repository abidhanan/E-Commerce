@extends('SuperAdmin.Template.Index')

@section('title', 'Create Blog')

@push('styles')
    <style>
        .trix-content blockquote {
            border-left: 4px solid #222;
            padding-left: 20px;
            margin: 30px 0;
        }

        .trix-content blockquote div {
            font-style: italic;
            font-size: 20px;
            line-height: 1.6;
        }
    </style>
    {{-- TRIX --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>Create Blog</h5>
        </div>

        <div class="card-body">

            <form action="{{ route('superadmin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- TITLE --}}
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required
                        value="{{ old('title') }}">

                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CATEGORY (SELECT2) --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id"
                        class="form-control select2-single @error('category_id') is-invalid @enderror" required>

                        <option value="">-- Select Category --</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- TAGS (SELECT2 MULTIPLE) --}}
                <div class="mb-3">
                    <label class="form-label">Tags</label>
                    <select name="tags[]" class="form-control select2-multiple" multiple>

                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"
                                {{ collect(old('tags'))->contains($tag->id) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- IMAGE --}}
                <div class="mb-3">
                    <label class="form-label">Thumbnail</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <small class="text-muted">
                        Max 2MB | jpg, jpeg, png, webp
                    </small>
                </div>

                {{-- CONTENT (TRIX) --}}
                <div class="mb-3">
                    <label class="form-label">Content</label>

                    <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                    <trix-editor input="content"></trix-editor>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('superadmin.blogs.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- TRIX --}}
    <script src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>

    {{-- SELECT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // disable upload trix
        document.addEventListener("trix-file-accept", function(e) {
            e.preventDefault();
        });

        // init select2
        $(document).ready(function() {

            $('.select2-single').select2({
                placeholder: "Select Category",
                width: '100%'
            });

            $('.select2-multiple').select2({
                placeholder: "Select Tags",
                width: '100%'
            });

        });
    </script>
@endpush
