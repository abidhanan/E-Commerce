@extends('Admin.Template.index')
@section('content')
    <div class="container mt-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Collections',
            'headingClass' => 'mb-0',
            'createRoute' => route('admin.collections.create'),
            'createLabel' => '+ Add',
            'createClass' => 'btn btn-primary btn-sm',
            'collapseId' => 'collectionsInfoCollapse',
            'helpTitle' => 'Keterangan Collections',
            'messages' => [
                'Halaman ini menampilkan daftar collection yang dipakai untuk mengelompokkan produk berdasarkan tema atau seri tertentu.',
                'Gunakan kolom pencarian untuk menemukan collection lebih cepat, lalu kelola datanya melalui aksi edit atau hapus pada tabel.',
                'Anda juga bisa langsung mengatur produk mana saja dari seri ini yang akan ditampilkan di halaman depan melalui tombol Showcase.'
            ],
        ])

        <div class="mb-3">
            {{-- Perbaikan placeholder dari "category" menjadi "collection" dan matikan autocomplete --}}
            <input type="text" id="search" class="form-control" placeholder="Search collection..." autocomplete="off">
        </div>

        <div id="collection-table-container" class="transition-opacity duration-300">
            @include('admin.collections.partials.table', ['collections' => $collections])
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const tableContainer = document.getElementById('collection-table-container');
            let debounceTimer;

            // 1. ENGINE PENCARIAN DENGAN DEBOUNCE (Menahan request agar server tidak kehabisan napas)
            searchInput.addEventListener('keyup', function() {
                clearTimeout(debounceTimer);
                let searchValue = this.value;

                debounceTimer = setTimeout(function() {
                    tableContainer.style.opacity = '0.5'; // Indikator visual memuat data

                    fetch(`{{ route('admin.collections.index') }}?search=${encodeURIComponent(searchValue)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Koneksi peladen gagal.');
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
                }, 400); // Menahan eksekusi selama 400 milidetik
            });

            // 2. ENGINE PENYELESUT PAGINASI AJAX (Mencegah reload halaman penuh)
            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.pagination a');
                
                if (paginationLink && tableContainer.contains(paginationLink)) {
                    e.preventDefault(); // Cegah refresh halaman bawaan browser
                    
                    let url = paginationLink.getAttribute('href');
                    let currentSearch = searchInput.value;

                    // Kawal kata kunci pencarian agar ikut terbawa ke halaman berikutnya
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