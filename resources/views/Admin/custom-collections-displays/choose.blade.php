@extends('Admin.Template.index')
@section('title', 'Pilih Produk Collection')

@push('styles')
    <style>
        .collection-summary-image {
            width: 100%;
            max-width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            background: #f3f3f3;
        }

        .product-card {
            border: 1px solid var(--border);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
        }

        .product-card.is-selected {
            border-color: #212529;
            box-shadow: 0 10px 20px rgba(33, 37, 41, 0.10);
        }

        .product-card-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            background: #f3f3f3;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <div>
                <a href="{{ route('admin.custom-collections-display.index') }}" class="btn btn-link px-0 text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Kembali ke daftar collection
                </a>
                <h4 class="mb-1">Pilih Produk untuk {{ $collection->name }}</h4>
                <p class="text-muted mb-0">Hanya produk dari collection ini yang bisa dipilih. Maksimal {{ $maxProducts }} produk.</p>
            </div>

            <span class="badge text-bg-dark fs-6" id="selected-count-badge">{{ count($selected) }} / {{ $maxProducts }} dipilih</span>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card p-3 p-lg-4 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-md-auto">
                    <img src="{{ $collection->img ? asset('storage/' . $collection->img) : 'https://via.placeholder.com/360x360?text=Collection' }}"
                        alt="{{ $collection->name }}" class="collection-summary-image">
                </div>
                <div class="col">
                    <h5 class="mb-2">{{ $collection->name }}</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge text-bg-light border">{{ $collection->active_products_count }} produk aktif</span>
                        <span class="badge text-bg-light border">Maksimal {{ $maxProducts }} produk display</span>
                    </div>
                    <p class="text-muted mt-3 mb-0">Simpan selection setelah selesai memilih produk yang ingin ditampilkan pada section custom collection.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.custom-collections-display.updateAll', $collection) }}" method="POST" id="custom-collection-form">
            @csrf
            <div id="selected-products-inputs"></div>

            <div class="card p-3 mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-8">
                        <label for="search" class="form-label mb-1">Cari produk dalam collection ini</label>
                        <input type="text" id="search" class="form-control" placeholder="Cari nama produk...">
                    </div>
                    <div class="col-lg-4">
                        <div class="small text-muted pt-lg-4">
                            Produk nonaktif tidak akan ditampilkan di halaman ini.
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="product-container"></div>

            <div class="text-center my-4" id="loading" style="display:none;">
                <div class="spinner-border text-dark" role="status"></div>
            </div>

            <div class="text-center my-4 text-muted" id="empty" style="display:none;">
                Tidak ada produk yang cocok untuk collection ini.
            </div>

            <div class="text-center my-4" id="load-more-wrapper" style="display:none;">
                <button type="button" class="btn btn-outline-dark" id="load-more-button">Muat lebih banyak</button>
            </div>

            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mt-4">
                <p class="text-muted mb-0">Selection saat ini akan menggantikan custom collection yang sebelumnya aktif.</p>
                <button class="btn btn-dark px-4">Simpan Custom Collection</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const maxProducts = {{ $maxProducts }};
        const loadUrl = @json(route('admin.custom-collections-display.load', $collection));
        const selectedGlobal = @json(array_values($selected ?? []));

        let page = 1;
        let loading = false;
        let hasMore = true;
        let search = '';

        function getSelectedCount() {
            return selectedGlobal.length;
        }

        function updateSelectedBadge() {
            document.getElementById('selected-count-badge').textContent = `${getSelectedCount()} / ${maxProducts} dipilih`;
        }

        function syncHiddenInputs() {
            document.getElementById('selected-products-inputs').innerHTML = selectedGlobal
                .map((id) => `<input type="hidden" name="product_ids[]" value="${id}">`)
                .join('');
        }

        function updateSelectionAvailability() {
            const reachedLimit = getSelectedCount() >= maxProducts;

            document.querySelectorAll('.product-checkbox').forEach((checkbox) => {
                checkbox.disabled = reachedLimit && !checkbox.checked;
            });
        }

        function renderProduct(product) {
            const image = (product.images || []).find((img) => Number(img.is_primary) === 1) || (product.images || [])[0];
            const isChecked = selectedGlobal.includes(product.id);
            const disabled = !isChecked && getSelectedCount() >= maxProducts;

            return `
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 p-3 product-card ${isChecked ? 'is-selected' : ''}" data-product-card="${product.id}">
                        <div class="position-relative mb-3">
                            <img src="${image ? '/storage/' + image.image : 'https://via.placeholder.com/600x600?text=Product'}"
                                alt="${product.name}" class="product-card-image">
                            ${isChecked ? '<span class="badge text-bg-dark position-absolute top-0 end-0 m-3">Dipilih</span>' : ''}
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-1">${product.name}</h6>
                            <p class="text-muted small mb-0">ID Produk: ${product.id}</p>
                        </div>

                        <div class="form-check mt-auto">
                            <input type="checkbox"
                                value="${product.id}"
                                class="form-check-input product-checkbox"
                                id="product-${product.id}"
                                ${isChecked ? 'checked' : ''}
                                ${disabled ? 'disabled' : ''}>
                            <label class="form-check-label" for="product-${product.id}">
                                Tampilkan produk ini
                            </label>
                        </div>
                    </div>
                </div>
            `;
        }

        function updateLoadMoreButton() {
            document.getElementById('load-more-wrapper').style.display = hasMore ? 'block' : 'none';
        }

        async function loadProducts(reset = false) {
            if (loading || (!hasMore && !reset)) {
                return;
            }

            loading = true;
            document.getElementById('loading').style.display = 'block';

            try {
                const response = await fetch(`${loadUrl}?page=${page}&search=${encodeURIComponent(search)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const result = await response.json();
                const container = document.getElementById('product-container');

                if (reset) {
                    container.innerHTML = '';
                }

                if (result.data.length === 0 && page === 1) {
                    document.getElementById('empty').style.display = 'block';
                } else {
                    document.getElementById('empty').style.display = 'none';
                }

                result.data.forEach((product) => {
                    container.insertAdjacentHTML('beforeend', renderProduct(product));
                });

                hasMore = result.has_more;
                page += 1;
                updateLoadMoreButton();
                updateSelectedBadge();
                syncHiddenInputs();
                updateSelectionAvailability();
            } catch (error) {
                document.getElementById('product-container').innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger mb-0">Terjadi error saat memuat produk.</div>
                    </div>
                `;
                document.getElementById('load-more-wrapper').style.display = 'none';
            } finally {
                loading = false;
                document.getElementById('loading').style.display = 'none';
            }
        }

        document.getElementById('load-more-button').addEventListener('click', function() {
            loadProducts();
        });

        let searchTimeout;
        document.getElementById('search').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                search = this.value;
                page = 1;
                hasMore = true;
                loadProducts(true);
            }, 350);
        });

        document.addEventListener('change', function(event) {
            if (!event.target.classList.contains('product-checkbox')) {
                return;
            }

            const productId = Number(event.target.value);
            const card = document.querySelector(`[data-product-card="${productId}"]`);

            if (event.target.checked) {
                if (getSelectedCount() >= maxProducts) {
                    event.target.checked = false;
                    window.alert(`Maksimal ${maxProducts} produk.`);
                    return;
                }

                if (!selectedGlobal.includes(productId)) {
                    selectedGlobal.push(productId);
                }
            } else {
                const index = selectedGlobal.indexOf(productId);

                if (index !== -1) {
                    selectedGlobal.splice(index, 1);
                }
            }

            if (card) {
                card.classList.toggle('is-selected', event.target.checked);

                const existingBadge = card.querySelector('.badge');
                if (event.target.checked && !existingBadge) {
                    card.querySelector('.position-relative').insertAdjacentHTML(
                        'beforeend',
                        '<span class="badge text-bg-dark position-absolute top-0 end-0 m-3">Dipilih</span>'
                    );
                }

                if (!event.target.checked && existingBadge) {
                    existingBadge.remove();
                }
            }

            updateSelectedBadge();
            syncHiddenInputs();
            updateSelectionAvailability();
        });

        updateSelectedBadge();
        syncHiddenInputs();
        loadProducts(true);
    </script>
@endpush
