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
            <input type="text" id="search" class="form-control" placeholder="Search category...">
        </div>

        <div id="category-table">
            @include('admin.categories.partials.table', ['categories' => $categories])
        </div>

    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let search = this.value;

            fetch(`{{ route('admin.categories.index') }}?search=` + search, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('category-table').innerHTML = data;
                });
        });
    </script>
@endsection
