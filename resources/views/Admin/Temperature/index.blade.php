@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Data Temperature',
            'createRoute' => route('admin.temperatures.create'),
            'createLabel' => '+ Tambah Temperature',
            'createClass' => 'btn btn-success',
            'collapseId' => 'temperatureInfoCollapse',
            'helpTitle' => 'Keterangan Temperature',
            'messages' => [
                'Halaman ini menampilkan rentang suhu referensi yang membantu menjelaskan kondisi ideal pemakaian suatu produk.',
                'Perhatikan label range dan deskripsi tiap suhu agar panduan temperatur yang tampil pada admin tetap mudah dipahami.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari temperature...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Temperature</th>
                                <th>Description</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @include('Admin.Temperature.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3" id="paginationWrapper">
                    {{ $temperatures->links() }}
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
            url = url ?? "{{ route('admin.temperatures.index') }}?search=" + search;

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
