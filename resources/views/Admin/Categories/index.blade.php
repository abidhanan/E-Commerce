@extends('Admin.Template.index')
@section('content')
    <div class="container mt-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Categories',
            'headingClass' => 'mb-0',
            'createRoute' => route('admin.categories.create'),
            'createLabel' => '+ Add',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'categoriesInfoCollapse',
            'helpTitle' => 'Keterangan Categories',
            'messages' => [
                'Halaman ini berisi kategori produk yang digunakan untuk mengatur pengelompokan item di katalog.',
                'Gunakan pencarian untuk menemukan kategori tertentu dan cek struktur kategorinya sebelum melakukan edit atau hapus data.',
            ],
        ])

        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search category..." autocomplete="off">
        </div>

        <div id="category-table" class="transition-opacity duration-300">
            @include('admin.categories.partials.table', ['categories' => $categories])
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableContainer = document.getElementById('category-table');
            let debounceTimer;

            // 1. Eksekusi Pencarian dengan Debounce (Menahan request sampai admin berhenti mengetik)
            searchInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                let searchValue = this.value;

                debounceTimer = setTimeout(function() {
                    tableContainer.style.opacity = '0.5'; // Indikator visual loading

                    // Gunakan encodeURIComponent mutlak untuk mencegah karakter khusus merusak URL
                    fetch(`{{ route('admin.categories.index') }}?search=${encodeURIComponent(searchValue)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Koneksi ke peladen gagal.');
                        return response.text();
                    })
                    .then(data => {
                        tableContainer.innerHTML = data;
                        tableContainer.style.opacity = '1';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.style.opacity = '1';
                    });
                }, 400); // Tahan selama 400 milidetik
            });

            // 2. Pertahanan Paginasi (Event Delegation)
            // Menangkap klik pada tombol paginasi yang baru saja disuntikkan oleh AJAX
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.pagination a');
                
                if (paginationLink && tableContainer.contains(paginationLink)) {
                    e.preventDefault(); // Cegah halaman melakukan refresh penuh
                    
                    let url = paginationLink.getAttribute('href');
                    let currentSearch = searchInput.value;

                    // Pastikan parameter pencarian ikut terbawa ke halaman selanjutnya
                    if (currentSearch && !url.includes('search=')) {
                        url += (url.includes('?') ? '&' : '?') + 'search=' + encodeURIComponent(currentSearch);
                    }

                    tableContainer.style.opacity = '0.5';

                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => response.text())
                    .then(data => {
                        tableContainer.innerHTML = data;
                        tableContainer.style.opacity = '1';
                    });
                }
            });
        });
    </script>
@endsection