@extends('Admin.Template.index')
@section('title', 'Edit Display')

@push('css')
    <style>
        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px dashed var(--bs-border-color);
            padding: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="mb-4">
            <h4 class="mb-1">Edit Konfigurasi Display</h4>
            <p class="text-muted mb-0">Perbarui banner, teks berjalan, dan tautan kampanye yang sedang tayang.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.displays.update', $display->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8">
                    @for ($i = 1; $i <= 3; $i++)
                        @php
                            $path = "image_{$i}_path";
                            $title = "image_{$i}_title";
                            $subTitle = "image_{$i}_sub_title";
                            $link = "image_{$i}_link";
                            $activeField = "image_{$i}_is_active";
                            
                            $labelImage = $i === 1 ? 'Hero 1 (Layar Penuh Utama)' : ($i === 2 ? 'Hero 2 (Kampanye Kolaborasi)' : "Hero $i (Tambahan)");
                        @endphp

                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom border-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">{{ $labelImage }}</h6>
                                
                                {{-- SAKELAR AKTIF/NONAKTIF --}}
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" role="switch" name="{{ $activeField }}" id="{{ $activeField }}" {{ old($activeField, $display->$activeField ?? true) ? 'checked' : '' }} value="1">
                                    <label class="form-check-label text-muted small fw-bold" for="{{ $activeField }}">Tayangkan</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <label class="form-label text-muted small fw-bold text-uppercase">Gambar Saat Ini / Ubah</label>
                                        
                                        @if($display->$path)
                                            <div class="position-relative mb-2">
                                                <img src="{{ asset('storage/' . $display->$path) }}" class="w-100 rounded border" style="height: 120px; object-fit: cover;">
                                            </div>
                                        @endif
                                        
                                        <input type="file" name="{{ $path }}" class="form-control form-control-sm image-input" data-preview="preview_{{ $i }}" accept="image/jpeg,image/png,image/webp">
                                        <img id="preview_{{ $i }}" class="image-preview" src="" style="display:none;">
                                        <small class="text-muted d-block mt-2" style="font-size: 11px;">Maks 3MB. Biarkan kosong jika tidak mengubah gambar.</small>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold text-uppercase">Judul Utama</label>
                                            <input type="text" name="{{ $title }}" class="form-control" placeholder="Teks besar (Misal: BUILD YOUR)" value="{{ old($title, $display->$title) }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-muted small fw-bold text-uppercase">Sub-Judul</label>
                                            <input type="text" name="{{ $subTitle }}" class="form-control" placeholder="Teks pendukung (Misal: Exclusive Collaboration)" value="{{ old($subTitle, $display->$subTitle) }}">
                                        </div>
                                        <div>
                                            <label class="form-label text-muted small fw-bold text-uppercase">Tautan / URL Tujuan <i class="bi bi-link-45deg"></i></label>
                                            <input type="url" name="{{ $link }}" class="form-control" placeholder="https://domain.com/collection/..." value="{{ old($link, $display->$link) }}">
                                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Kosongkan jika ingin mengarah ke halaman shop umum.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4 border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-white border-bottom border-light">
                            <h6 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Pengaturan Tambahan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Running Text (Teks Berjalan)</label>
                                <textarea name="running_text" class="form-control" rows="4" placeholder="Misal: FREE SHIPPING ON ALL ORDERS OVER RP 500.000">{{ old('running_text', $display->running_text) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 fw-bold text-uppercase" style="letter-spacing: 1px;">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.displays.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            });
        });
    </script>
@endpush