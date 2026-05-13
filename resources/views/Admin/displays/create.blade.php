@extends('Admin.Template.index')
@section('title', 'Display Form')

@push('css')
    <style>
        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">
            {{ isset($display) ? 'Edit Display' : 'Create Display' }}
        </h4>

        <form action="{{ isset($display) ? route('admin.displays.update', $display->id) : route('admin.displays.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($display))
                @method('PUT')
            @endif

            {{-- ================= HERO IMAGE ================= --}}
            <div class="card mb-4 p-3">
                <h5 class="mb-3">Hero Image</h5>

                @php
                    $path = 'image_1_path';
                    $title = 'image_1_title';
                    $subTitle = 'image_1_sub_title';
                @endphp

                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="{{ $path }}" class="form-control image-input"
                            data-preview="preview_1">

                        <img id="preview_1" class="image-preview"
                            src="{{ isset($display) && $display->$path ? asset('storage/' . $display->$path) : '' }}"
                            style="{{ isset($display) && $display->$path ? '' : 'display:none;' }}">
                    </div>

                    <div class="col-md-6">
                        <input type="text" name="{{ $title }}" class="form-control mb-2" placeholder="Title"
                            value="{{ $display->$title ?? '' }}">

                        <input type="text" name="{{ $subTitle }}" class="form-control" placeholder="Sub Title"
                            value="{{ $display->$subTitle ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- ================= BEST SELLER ================= --}}
            <div class="card mb-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Best Seller</h5>

                    <a href="{{ route('admin.bestsellers.index') }}" class="btn btn-dark btn-sm">
                        Kelola Best Seller
                    </a>
                </div>

                <small class="text-muted mt-2">
                    Kelola produk best seller melalui halaman terpisah
                </small>
            </div>

            {{-- ================= IMAGE 2 & 3 ================= --}}
            <div class="row">
                @for ($i = 2; $i <= 3; $i++)
                    @php
                        $path = "image_{$i}_path";
                        $title = "image_{$i}_title";
                        $subTitle = "image_{$i}_sub_title";
                    @endphp

                    <div class="col-md-6 mb-4">
                        <div class="card p-3">

                            <h6>Image {{ $i }}</h6>

                            <input type="file" name="{{ $path }}" class="form-control image-input"
                                data-preview="preview_{{ $i }}">

                            <img id="preview_{{ $i }}" class="image-preview"
                                src="{{ isset($display) && $display->$path ? asset('storage/' . $display->$path) : '' }}"
                                style="{{ isset($display) && $display->$path ? '' : 'display:none;' }}">

                            <input type="text" name="{{ $title }}" class="form-control mt-2"
                                placeholder="Title Image {{ $i }}" value="{{ $display->$title ?? '' }}">

                            <input type="text" name="{{ $subTitle }}" class="form-control mt-2"
                                placeholder="Sub Title Image {{ $i }}" value="{{ $display->$subTitle ?? '' }}">

                        </div>
                    </div>
                @endfor
            </div>
            {{-- ================= BEST SELLER ================= --}}
            <div class="card mb-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Custom Collections</h5>

                    <a href="{{ route('admin.custom-collections-display.index') }}" class="btn btn-dark btn-sm">
                        Kelola Custom Collections
                    </a>
                </div>

                <small class="text-muted mt-2">
                    Kelola produk custom collections melalui halaman terpisah
                </small>
            </div>
            <div class="card mb-4 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Social Media & Official Stores</h5>

                    <a href="{{ route('admin.social-links.index') }}" class="btn btn-dark btn-sm">
                        Kelola Link Sosial
                    </a>
                </div>

                <small class="text-muted mt-2">
                    Atur link sosial media dan official store seperti Shopee, Tokopedia, Zalora, dan TikTok Shop.
                </small>
            </div>
            {{-- ================= RUNNING TEXT ================= --}}
            <div class="card mb-4 p-3">
                <h5>Running Text</h5>

                <textarea name="running_text" class="form-control" rows="3" placeholder="Masukkan running text...">{{ $display->running_text ?? '' }}</textarea>
            </div>

            {{-- ================= SUBMIT ================= --}}
            <button class="btn btn-primary">
                {{ isset($display) ? 'Update' : 'Submit' }}
            </button>

        </form>

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.image-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const previewId = this.getAttribute('data-preview');
                const preview = document.getElementById(previewId);

                const file = e.target.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                }
            });
        });
    </script>
@endpush
