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
            ],
        ])

        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search category...">
        </div>

        <div id="category-table">
            @include('admin.collections.partials.table', ['collections' => $collections])
        </div>

    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let search = this.value;

            fetch(`{{ route('admin.collections.index') }}?search=` + search, {
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
