@extends('Admin.Template.index')
@section('title', 'Create Size Guide')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-3">Create Size Guide</h4>

        <form action="{{ route('admin.size-guides.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- TYPE --}}
            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" placeholder="ex: shirt, bag" required>
            </div>

            {{-- NAME --}}
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
            </div>
            <div class="mb-3">
                <Label>Image</Label>
                <input type="file" name="img" class="form-control">
                <small class="form-text text-muted">Format: jpg, jpeg, png. Max size: 2MB.</small>
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

            <button class="btn btn-success mt-3">Save</button>
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

            btnAddSize.addEventListener('click', addSize);

            // =============================
            // ADD SIZE
            // =============================
            function addSize() {
                let html = `
        <div class="card border mb-3 p-2 size-card">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <input type="text"
                    name="data[${sizeIndex}][size]"
                    class="form-control form-control-sm w-25"
                    placeholder="Size (S, M)">

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
                sizeIndex++;
            }

            // =============================
            // EVENT HANDLER
            // =============================
            container.addEventListener('click', function(e) {

                // ADD VARIANT
                if (e.target.classList.contains('btn-add-variant')) {

                    const sizeCard = e.target.closest('.size-card');
                    const variantContainer = sizeCard.querySelector('.variant-container');

                    let variantIndex = variantContainer.children.length;
                    let sizeIdx = [...container.children].indexOf(sizeCard);

                    let html = `
            <div class="row g-2 mb-2 variant-row">

                <div class="col-md-3">
                    <input type="text"
                        name="data[${sizeIdx}][measurements][${variantIndex}][label]"
                        class="form-control form-control-sm"
                        placeholder="Field (Chest)">
                </div>

                <div class="col-md-2">
                    <select name="data[${sizeIdx}][measurements][${variantIndex}][type]"
                        class="form-control form-control-sm type-select">
                        <option value="simple">Simple</option>
                        <option value="range">Range</option>
                    </select>
                </div>

                {{-- SIMPLE --}}
                <div class="col-md-4 value-field">
                    <div class="d-flex gap-1">
                        <input type="number"
                            name="data[${sizeIdx}][measurements][${variantIndex}][value]"
                            class="form-control form-control-sm"
                            placeholder="Value">

                        <select name="data[${sizeIdx}][measurements][${variantIndex}][unit]"
                            class="form-control form-control-sm">
                            <option value="cm">cm</option>
                            <option value="inch">inch</option>
                            <option value="m">m</option>
                        </select>
                    </div>
                </div>

                {{-- RANGE --}}
                <div class="col-md-4 range-field d-none">
                    <div class="d-flex gap-1">
                        <input type="number"
                            name="data[${sizeIdx}][measurements][${variantIndex}][min]"
                            class="form-control form-control-sm"
                            placeholder="Min">

                        <input type="number"
                            name="data[${sizeIdx}][measurements][${variantIndex}][max]"
                            class="form-control form-control-sm"
                            placeholder="Max">

                        <select name="data[${sizeIdx}][measurements][${variantIndex}][unit]"
                            class="form-control form-control-sm">
                            <option value="cm">cm</option>
                            <option value="inch">inch</option>
                            <option value="m">m</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <button type="button"
                        class="btn btn-danger btn-sm btn-remove-variant">
                        Hapus
                    </button>
                </div>

            </div>
            `;

                    variantContainer.insertAdjacentHTML('beforeend', html);
                }

                // REMOVE VARIANT
                if (e.target.classList.contains('btn-remove-variant')) {
                    e.target.closest('.variant-row').remove();
                }

                // REMOVE SIZE
                if (e.target.classList.contains('btn-remove-size')) {
                    e.target.closest('.size-card').remove();
                }

                updatePreview();
            });

            // =============================
            // TYPE TOGGLE
            // =============================
            container.addEventListener('change', function(e) {

                if (e.target.classList.contains('type-select')) {

                    const row = e.target.closest('.variant-row');
                    const valueField = row.querySelector('.value-field');
                    const rangeField = row.querySelector('.range-field');

                    if (e.target.value === 'range') {
                        valueField.classList.add('d-none');
                        rangeField.classList.remove('d-none');
                    } else {
                        valueField.classList.remove('d-none');
                        rangeField.classList.add('d-none');
                    }
                }

                updatePreview();
            });

            container.addEventListener('input', updatePreview);

            // =============================
            // PREVIEW (ROWSPAN FIX)
            // =============================
            function updatePreview() {

                let table = `
        <h5 class="mt-4">Preview</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Size</th>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
        `;

                const sizeCards = container.querySelectorAll('.size-card');

                sizeCards.forEach(card => {

                    const size = card.querySelector('[name*="[size]"]').value;
                    const variants = card.querySelectorAll('.variant-row');

                    variants.forEach((row, index) => {

                        const label = row.querySelector('[name*="[label]"]').value;
                        const type = row.querySelector('[name*="[type]"]').value;

                        let unit = row.querySelector('select[name*="[unit]"]')?.value || '';

                        let value = '';

                        if (type === 'range') {
                            const min = row.querySelector('[name*="[min]"]').value;
                            const max = row.querySelector('[name*="[max]"]').value;
                            value = `${min} - ${max} ${unit}`;
                        } else {
                            const val = row.querySelector('[name*="[value]"]').value;
                            value = `${val} ${unit}`;
                        }

                        if (size || label || value) {

                            table += `<tr>`;

                            if (index === 0) {
                                table +=
                                    `<td rowspan="${variants.length}" class="text-center align-middle fw-bold">${size}</td>`;
                            }

                            table += `
                        <td>${label}</td>
                        <td>${value}</td>
                    </tr>
                    `;
                        }

                    });

                });

                table += '</tbody></table>';
                preview.innerHTML = table;
            }

        });
    </script>
@endpush
