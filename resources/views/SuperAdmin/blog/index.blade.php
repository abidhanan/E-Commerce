@extends('SuperAdmin.Template.Index')

@section('content')
    <div class="container">

        <h3 class="mb-3">Data Blog</h3>

        {{-- SEARCH --}}
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Cari judul blog...">
        </div>

        {{-- TABLE --}}
        <div id="table-data">
            @include('SuperAdmin.blog.partials.table', ['posts' => $posts])
        </div>

    </div>

    {{-- 🔥 MODAL PREVIEW --}}
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="previewTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <img id="previewImage" class="img-fluid mb-3" style="display:none; border-radius:8px;">
                    <div class="trix-content" id="previewContent"></div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .trix-content {
            line-height: 1.8;
            font-size: 16px;
        }

        .trix-content h2 {
            font-size: 26px;
            margin-bottom: 15px;
        }

        .trix-content blockquote {
            border-left: 4px solid #222;
            padding-left: 15px;
            margin: 20px 0;
        }

        .trix-content blockquote div {
            font-style: italic;
        }

        .trix-content ul,
        .trix-content ol {
            padding-left: 20px;
        }

        .trix-content img {
            max-width: 100%;
            border-radius: 8px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // 🔍 SEARCH AJAX
        document.getElementById('search').addEventListener('keyup', function() {
            let search = this.value;

            fetch(`{{ route('superadmin.blogs.index') }}?search=${encodeURIComponent(search)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(data => {
                    document.getElementById('table-data').innerHTML = data;
                });
        });


        // 🔥 PREVIEW MODAL (FIX FINAL)
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-preview')) {

                let title = e.target.dataset.title;
                let content = atob(e.target.dataset.content); // 🔥 decode base64
                let image = e.target.dataset.image;

                document.getElementById('previewTitle').innerText = title;
                document.getElementById('previewContent').innerHTML = content;

                let img = document.getElementById('previewImage');

                if (image) {
                    img.src = image;
                    img.style.display = 'block';
                } else {
                    img.style.display = 'none';
                }

                let modal = new bootstrap.Modal(document.getElementById('previewModal'));
                modal.show();
            }
        });
    </script>
@endpush
