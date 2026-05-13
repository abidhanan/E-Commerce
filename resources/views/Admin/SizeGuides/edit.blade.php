@extends('Admin.Template.index')
@section('title', 'Edit Size Guide')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-3">Edit Size Guide</h4>

        <form action="{{ route('admin.size-guides.update', $sizeGuide->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- TYPE --}}
            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="{{ old('type', $sizeGuide->type) }}" required>
            </div>

            {{-- NAME --}}
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $sizeGuide->name) }}">
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="img" class="form-control">
                <small class="form-text text-muted">Format: jpg, jpeg, png. Max size: 2MB.</small>
            </div>

            <div class="mb-3">
                    <label>Current Image</label><br>
                    @if ($sizeGuide->img)
                        <img src="{{ asset('storage/' . $sizeGuide->img) }}" alt="Image" style="height: 80px;">
                    @else
                        <span class="text-muted">No image</span>
                    @endif
            </div>

            {{-- DYNAMIC --}}
            <div class="mb-3">
                <label>Size Guide Data</label>

                <div id="dynamic-fields"></div>

                <button type="button" id="btn-add-size" class="btn btn-primary btn-sm mt-2">
                    + Tambah Size
                </button>
            </div>

            {{-- PREVIEW --}}
            <div id="preview-table"></div>

            <button class="btn btn-success mt-3">Update</button>
            <a href="{{ route('admin.size-guides.index') }}" class="btn btn-secondary mt-3">Back</a>

        </form>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let sizeIndex = 0;

            const container = document.getElementById('dynamic-fields');
            const btnAddSize = document.getElementById('btn-add-size');
            const preview = document.getElementById('preview-table');

            // 🔥 ambil data lama
            const rawData = @json(old('data', $sizeGuide->data['sizes'] ?? []));
            const existingData = Array.isArray(rawData) ? rawData : [];

            btnAddSize.addEventListener('click', () => addSize());

            // =============================
            // ADD SIZE (SUPPORT PREFILL)
            // =============================
            function addSize(data = null) {

                let sizeVal = data?.size ?? '';

                let html = `
        <div class="card border mb-3 p-2 size-card">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <input type="text"
                    name="data[${sizeIndex}][size]"
                    value="${sizeVal}"
                    class="form-control form-control-sm w-25">

                <button type="button"
                    class="btn btn-danger btn-sm btn-remove-size">
                    Hapus Size
                </button>
            </div>

            <div class="variant-container"></div>

            <button type="button"
                class="btn btn-success btn-sm btn-add-variant mt-2">
                + Tambah Variant
            </button>

        </div>
        `;

                container.insertAdjacentHTML('beforeend', html);

                const currentCard = container.lastElementChild;

                // 🔥 load variant lama
                if (data?.measurements) {
                    data.measurements.forEach(m => {
                        addVariant(currentCard, sizeIndex, m);
                    });
                }

                sizeIndex++;
            }

            // =============================
            // ADD VARIANT (SUPPORT PREFILL)
            // =============================
            function addVariant(sizeCard, sizeIdx, data = null) {

                const variantContainer = sizeCard.querySelector('.variant-container');
                let variantIndex = variantContainer.children.length;

                let label = data?.label ?? '';
                let type = data?.type ?? 'simple';
                let value = data?.value ?? '';
                let min = data?.min ?? '';
                let max = data?.max ?? '';
                let unit = data?.unit ?? 'cm';

                let html = `
        <div class="row g-2 mb-2 variant-row">

            <div class="col-md-3">
                <input type="text"
                    name="data[${sizeIdx}][measurements][${variantIndex}][label]"
                    value="${label}"
                    class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <select name="data[${sizeIdx}][measurements][${variantIndex}][type]"
                    class="form-control form-control-sm type-select">
                    <option value="simple" ${type==='simple'?'selected':''}>Simple</option>
                    <option value="range" ${type==='range'?'selected':''}>Range</option>
                </select>
            </div>

            <div class="col-md-4 value-field ${type==='range'?'d-none':''}">
                <div class="d-flex gap-1">
                    <input type="number"
                        name="data[${sizeIdx}][measurements][${variantIndex}][value]"
                        value="${value}"
                        class="form-control form-control-sm">

                    <select name="data[${sizeIdx}][measurements][${variantIndex}][unit]"
                        class="form-control form-control-sm">
                        <option value="cm" ${unit==='cm'?'selected':''}>cm</option>
                        <option value="inch" ${unit==='inch'?'selected':''}>inch</option>
                        <option value="m" ${unit==='m'?'selected':''}>m</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4 range-field ${type!=='range'?'d-none':''}">
                <div class="d-flex gap-1">
                    <input type="number"
                        name="data[${sizeIdx}][measurements][${variantIndex}][min]"
                        value="${min}"
                        class="form-control form-control-sm">

                    <input type="number"
                        name="data[${sizeIdx}][measurements][${variantIndex}][max]"
                        value="${max}"
                        class="form-control form-control-sm">

                    <select name="data[${sizeIdx}][measurements][${variantIndex}][unit]"
                        class="form-control form-control-sm">
                        <option value="cm" ${unit==='cm'?'selected':''}>cm</option>
                        <option value="inch" ${unit==='inch'?'selected':''}>inch</option>
                        <option value="m" ${unit==='m'?'selected':''}>m</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm btn-remove-variant">
                    Hapus
                </button>
            </div>
        </div>
        `;

                variantContainer.insertAdjacentHTML('beforeend', html);
            }

            // =============================
            // LOAD DATA AWAL
            // =============================
            if (existingData.length) {
                existingData.forEach(item => addSize(item));
            } else {
                addSize();
            }

            // =============================
            // EVENT HANDLER
            // =============================
            container.addEventListener('click', function(e) {

                if (e.target.classList.contains('btn-add-variant')) {
                    const card = e.target.closest('.size-card');
                    const sizeIdx = [...container.children].indexOf(card);
                    addVariant(card, sizeIdx);
                }

                if (e.target.classList.contains('btn-remove-variant')) {
                    e.target.closest('.variant-row').remove();
                }

                if (e.target.classList.contains('btn-remove-size')) {
                    e.target.closest('.size-card').remove();
                }

                updatePreview();
            });

            container.addEventListener('change', function(e) {
                if (e.target.classList.contains('type-select')) {
                    const row = e.target.closest('.variant-row');
                    row.querySelector('.value-field').classList.toggle('d-none', e.target.value ===
                    'range');
                    row.querySelector('.range-field').classList.toggle('d-none', e.target.value !==
                    'range');
                }
                updatePreview();
            });

            container.addEventListener('input', updatePreview);

            // =============================
            // PREVIEW
            // =============================
            function updatePreview() {

                let table = `<table class="table table-bordered"><thead>
            <tr><th>Size</th><th>Field</th><th>Value</th></tr>
        </thead><tbody>`;

                container.querySelectorAll('.size-card').forEach(card => {

                    const size = card.querySelector('[name*="[size]"]').value;
                    const variants = card.querySelectorAll('.variant-row');

                    variants.forEach((row, i) => {

                        let label = row.querySelector('[name*="[label]"]').value;
                        let type = row.querySelector('[name*="[type]"]').value;
                        let unit = row.querySelector('[name*="[unit]"]').value;

                        let value = type === 'range' ?
                            `${row.querySelector('[name*="[min]"]').value} - ${row.querySelector('[name*="[max]"]').value} ${unit}` :
                            `${row.querySelector('[name*="[value]"]').value} ${unit}`;

                        table += `<tr>`;
                        if (i === 0) table += `<td rowspan="${variants.length}">${size}</td>`;
                        table += `<td>${label}</td><td>${value}</td></tr>`;
                    });
                });

                table += `</tbody></table>`;
                preview.innerHTML = table;
            }

        });
    </script>
@endpush
