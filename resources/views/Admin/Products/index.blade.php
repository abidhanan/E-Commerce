@extends('Admin.Template.index')

@push('styles')
    <style>
        .product-page-header {
            gap: 1rem;
        }

        .product-page-actions {
            gap: 0.5rem;
        }

        .product-help-button {
            width: 34px;
            height: 34px;
            padding: 0;
            border-radius: 999px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .product-help-panel {
            border: 1px solid #dbe5f3;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        @media (max-width: 575.98px) {
            .product-page-header {
                align-items: stretch !important;
            }

            .product-page-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 product-page-header">
            <h4 class="fw-semibold mb-0">Data Produk</h4>

            <div class="d-flex align-items-center flex-wrap product-page-actions">
                <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                    + Tambah Produk
                </a>

                <button class="btn btn-outline-primary btn-sm product-help-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#productInfoCollapse" aria-expanded="false" aria-controls="productInfoCollapse"
                    title="Lihat keterangan tabel produk">
                    ?
                </button>
            </div>
        </div>

        <div class="collapse mb-4" id="productInfoCollapse">
            <div class="card shadow-sm border-0 product-help-panel">
                <div class="card-body">
                    <h6 class="fw-semibold mb-2">Keterangan Data Produk</h6>
                    <p class="text-muted mb-2">
                        Tabel ini menampilkan ringkasan produk yang sudah dibuat di admin, termasuk gambar utama,
                        kategori, collection, jumlah variant, dan status aktif produk.
                    </p>
                    <p class="text-muted mb-0">
                        Gunakan kolom pencarian untuk menemukan produk lebih cepat, lalu pakai tombol
                        <strong>Edit</strong> atau <strong>Hapus</strong> pada kolom aksi untuk mengelola data.
                    </p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari produk...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Gambar</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Collection</th>
                                <th>Variant</th>
                                <th>Status</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @include('Admin.Products.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        let timeout = null;

        function fetchProducts(url = null) {

            let search = document.getElementById('search').value;
            url = url ?? "{{ route('admin.products.index') }}?search=" + search;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('productTable').innerHTML = data.table;
                    document.getElementById('paginationWrapper').innerHTML = data.pagination;
                });
        }

        // realtime search with debounce
        document.getElementById('search').addEventListener('keyup', function() {

            clearTimeout(timeout);

            timeout = setTimeout(() => {
                fetchProducts();
            }, 400);

        });

        // ajax pagination click
        document.addEventListener('click', function(e) {

            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let url = e.target.closest('.pagination a').getAttribute('href');
                fetchProducts(url);
            }

        });
    </script>
@endpush
