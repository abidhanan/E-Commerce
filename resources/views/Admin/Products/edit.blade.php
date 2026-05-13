@extends('Admin.Template.index')
@push('css')
    <style>
        .text-warning-img {
            color: #d60b0b;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }

        .text-info {
            color: #064097 !important;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Edit Produk</h4>
                <small class="text-muted">Perbarui informasi produk</small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- LEFT --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">

                            <h6 class="fw-semibold mb-3">Informasi Produk</h6>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Collection</label>
                                <select name="collection_id" class="form-select">
                                    <option value="">-- None --</option>
                                    @foreach ($collections as $collection)
                                        @php
                                            $selectedCollection = old('collection_id', $product->collection_id);
                                        @endphp

                                        <option value="{{ $collection->id }}"
                                            {{ $selectedCollection == $collection->id ? 'selected' : '' }}>
                                            {{ $collection->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="form-control"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" rows="4" class="form-control">{{ $product->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Material</label>

                                <select name="material[]" class="form-select select2" multiple>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}"
                                            {{ in_array($material->id, (array) $product->material) ? 'selected' : '' }}>
                                            {{ $material->material }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="unisex" {{ $product->gender == 'unisex' ? 'selected' : '' }}>Unisex
                                        </option>
                                        <option value="pria" {{ $product->gender == 'pria' ? 'selected' : '' }}>Pria
                                        </option>
                                        <option value="wanita" {{ $product->gender == 'wanita' ? 'selected' : '' }}>Wanita
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Berat (gram)</label>
                                    <input type="number" name="weight" value="{{ $product->weight }}"
                                        class="form-control">
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-semibold mb-3">Spesifikasi Produk</h6>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Temperature</label>
                                    <input type="number" name="temperature" value="{{ $product->temperature }}"
                                        class="form-control" min="-10" max="30">
                                    <small class="form-text text-info">-10°= memberikan kehangatan, cocok untuk cuaca dingin
                                        <br> 30°= untuk suhu sangat panas, bahan breathable</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Intensity</label>
                                    <select name="intensity" class="form-select">
                                        <option value="low" {{ $product->intensity == 'low' ? 'selected' : '' }}>Low
                                        </option>
                                        <option value="high" {{ $product->intensity == 'high' ? 'selected' : '' }}>High
                                        </option>
                                    </select>
                                    <small class="form-text text-info">Low = Dioptimalkan untuk aktivitas ringan <br> High =
                                        Dioptomalkan untuk peforma tinggi</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Insulation</label>
                                    <input type="number" name="insulation" value="{{ $product->insulation }}"
                                        class="form-control" min="0" max="6">
                                    <small class="form-text text-info">0 = Cocok untuk cuaca hangat <br> 6 = Cocok untuk
                                        cuaca dingin</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Breathability</label>
                                    <input type="number" name="breathability" value="{{ $product->breathability }}"
                                        class="form-control" min="0" max="6">
                                    <small class="form-text text-info">0 = Sirkulasi udara rendah <br> 6 = Sirkulasi udara
                                        tinggi</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Size Guide</label>
                                    <select name="size_guide_id" class="form-select">
                                        <option value="">-- None --</option>
                                        @foreach ($sizeGuides as $sg)
                                            <option value="{{ $sg->id }}"
                                                {{ old('size_guide_id') == $sg->id ? 'selected' : '' }}>
                                                {{ $sg->name ?? $sg->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                {{-- RIGHT --}}
                <div class="col-lg-6">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Upload Gambar Baru</h6>

                            <input type="file" name="images[]" multiple accept="image/*" class="form-control"
                                onchange="previewImages(event)">
                            <small class="text-warning-img">Gambar Max 2Mb</small>

                            <div id="preview" class="d-flex flex-wrap gap-2 mt-3"></div>

                        </div>
                    </div>


                    <div class="card shadow-sm border-0">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Variant Produk</h6>
                                <button type="button" onclick="addVariant()" class="btn btn-success btn-sm">
                                    + Tambah
                                </button>
                            </div>

                            <div id="variant-wrapper" class="d-flex flex-column gap-2"></div>

                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-success px-4">
                    Update Produk
                </button>
            </div>

        </form>


        {{-- EXISTING IMAGES --}}
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">

                <h6 class="fw-semibold mb-3">Gambar Saat Ini</h6>

                <div class="row g-3">

                    @foreach ($product->images as $img)
                        <div class="col-md-3 col-6">

                            <div class="border rounded p-2 text-center h-100">

                                <img src="{{ asset('storage/' . $img->image) }}" class="img-fluid rounded mb-2"
                                    style="height:100px;width:100%;object-fit:cover;">

                                {{-- Status --}}
                                <div class="mb-2">

                                    @if ($img->is_primary)
                                        <span class="badge bg-success">Primary</span>
                                    @endif

                                    @if ($img->is_hover)
                                        <span class="badge bg-info">Hover</span>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="d-grid gap-1">

                                    @if (!$img->is_primary && !$img->is_hover)
                                        <form action="{{ route('admin.products.image.primary', $img->id) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-outline-success btn-sm w-100">
                                                Set Primary
                                            </button>
                                        </form>
                                    @endif

                                    @if (!$img->is_hover && $img->is_primary == false)
                                        <form action="{{ route('admin.products.image.hover', $img->id) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-outline-primary btn-sm w-100">
                                                Set Hover
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.products.image.delete', $img->id) }}" method="POST"
                                        data-confirm-message="Hapus gambar ini?" data-confirm-title="Hapus Gambar"
                                        data-confirm-button="Hapus" data-confirm-class="btn-danger">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm w-100">
                                            Hapus
                                        </button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>

        <script>
            let variantIndex = 0;

            function addVariant(data = null) {

                let sku = data?.sku ?? ''

                let size = data?.size ?? ''
                let price = data?.price ?? ''
                let stock = data?.stock ?? ''

                let html = `
<div class="card border">
<div class="card-body py-2">
<div class="row g-2 align-items-end">

<div class="col-md-3">
<input type="text"
name="variants[${variantIndex}][sku]"
value="${sku}"
class="form-control form-control-sm"
placeholder="SKU"
required>
</div>



<div class="col-md-2">
<input type="text"
name="variants[${variantIndex}][size]"
value="${size}"
class="form-control form-control-sm"
placeholder="Size"
required>
</div>

<div class="col-md-2">
<input type="number"
step="0.01"
name="variants[${variantIndex}][price]"
value="${price}"
class="form-control form-control-sm"
placeholder="Price"
required>
</div>

<div class="col-md-2">
<input type="number"
name="variants[${variantIndex}][stock]"
value="${stock}"
class="form-control form-control-sm"
placeholder="Stock"
min="0"
required>
</div>

<div class="col-md-1 text-end">
<button type="button"
class="btn btn-danger btn-sm"
onclick="this.closest('.card').remove()">
✕
</button>
</div>

</div>
</div>
</div>
`

                document.getElementById('variant-wrapper')
                    .insertAdjacentHTML('beforeend', html)

                variantIndex++
            }

            @foreach ($product->variants as $variant)

                addVariant({
                    sku: "{{ $variant->sku }}",
                    size: "{{ $variant->size }}",
                    price: "{{ $variant->price }}",
                    stock: "{{ $variant->stock }}"
                })
            @endforeach


            function previewImages(event) {

                const preview = document.getElementById('preview')
                preview.innerHTML = ''

                Array.from(event.target.files).forEach(file => {

                    const reader = new FileReader()

                    reader.onload = e => {

                        preview.innerHTML += `
<img src="${e.target.result}"
class="rounded border shadow-sm"
style="width:90px;height:90px;object-fit:cover;">
`

                    }

                    reader.readAsDataURL(file)

                })

            }
        </script>
    @endsection
    @push('styles')
        <style>
            /* Select2 container */
            .select2-container .select2-selection--multiple {
                min-height: 38px;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                padding: 4px 6px;
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection__choice {
                background-color: #e9ecef;
                color: #333;
            }

            /* Hover & focus */
            .select2-container--default .select2-selection--multiple:focus,
            .select2-container--default.select2-container--focus .select2-selection--multiple {
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
            }

            /* Selected tag */
            .select2-container--default .select2-selection__choice {
                background-color: #0d6efd;
                border: none;
                color: white;
                border-radius: 0.25rem;
                padding: 2px 6px;
                margin-top: 4px;
            }

            /* Remove button (x) */
            .select2-container--default .select2-selection__choice__remove {
                color: white;
                margin-right: 4px;
            }

            /* Dropdown */
            .select2-container--default .select2-results__option--highlighted {
                background-color: #0d6efd;
                color: white;
            }

            /* Search input */
            .select2-container .select2-search__field {
                margin-top: 4px;
            }

            /* Fix height single select */
            .select2-container .select2-selection--single {
                height: 38px;
                padding: 6px 12px;
                border-radius: 0.375rem;
                border: 1px solid #dee2e6;
            }

            /* Align text */
            .select2-container .select2-selection__rendered {
                line-height: 24px;
            }
        </style>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $('.select2').select2({
                width: '100%',
                placeholder: "Pilih Material",
                allowClear: false
            });
        </script>
    @endpush
