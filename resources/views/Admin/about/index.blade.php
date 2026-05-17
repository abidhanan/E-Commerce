@extends('Admin.Template.index')
@section('title', 'About Us')

@push('styles')
    {{-- TRIX --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">

    <style>
        /* Trix editor */
        trix-editor {
            min-height: 300px;
            background: var(--white);
        }

        /* Styling hasil content */
        .about-preview {
            max-width: 800px;
            margin-top: 30px;
            line-height: 1.8;
            font-size: 16px;
        }

        .about-preview h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .about-preview h2 {
            font-size: 26px;
            margin: 25px 0 10px;
        }

        .about-preview h3 {
            font-size: 20px;
            margin: 20px 0 10px;
        }

        .about-preview p {
            margin-bottom: 12px;
        }

        .about-preview ul {
            padding-left: 20px;
            margin-bottom: 15px;
        }

        .about-preview blockquote {
            border-left: 4px solid var(--gold);
            padding-left: 20px;
            margin: 30px 0;
        }

        .about-preview blockquote div {
            font-style: italic;
            font-size: 18px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        @include('Admin.partials.index-help-header', [
            'heading' => 'About Us',
            'collapseId' => 'aboutInfoCollapse',
            'helpTitle' => 'Keterangan About Us',
            'messages' => [
                'Halaman ini digunakan untuk mengatur judul dan isi halaman About Us yang tampil ke pengunjung storefront.',
                'Gunakan editor untuk menulis profil brand, cerita usaha, atau informasi penting lain yang ingin ditampilkan dengan format teks yang rapi.',
                'Bagian preview menampilkan konten yang sedang tersimpan agar perubahan lebih mudah dicek sebelum diperbarui lagi.',
            ],
        ])

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.aboutus.store') }}" method="POST">
            @csrf

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                    value="{{ $about->title ?? old('title') }}" required>

                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- TRIX --}}
            <div class="mb-3">
                <label class="form-label">Content</label>

                <input id="content" type="hidden" name="content" value="{{ $about->content ?? old('content') }}">

                <trix-editor input="content"></trix-editor>

                @error('content')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Simpan</button>
        </form>

        {{-- PREVIEW --}}
        @if (isset($about))
            <div class="about-preview mt-5">
                <h5>Preview</h5>
                <div class="border p-3">
                    {!! \App\Support\HtmlSanitizer::clean($about->content ?? '', ['p', 'br', 'b', 'strong', 'i', 'em', 'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'blockquote']) !!}
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    {{-- TRIX --}}
    <script src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>

    <script>
        // 🔥 DISABLE UPLOAD FILE / IMAGE
        document.addEventListener("trix-file-accept", function(e) {
            e.preventDefault();
        });

        // 🔥 OPTIONAL: disable paste image dari clipboard
        document.addEventListener("trix-attachment-add", function(e) {
            if (e.attachment.file) {
                e.preventDefault();
            }
        });
    </script>
@endpush
