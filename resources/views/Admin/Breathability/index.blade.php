@extends('Admin.Template.index')
@section('content')
    <div class="container py-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Data Breathability',
            'createRoute' => route('admin.breathabilities.create'),
            'createLabel' => '+ Tambah Breathability',
            'createClass' => 'btn btn-success',
            'collapseId' => 'breathabilityInfoCollapse',
            'helpTitle' => 'Keterangan Breathability',
            'messages' => [
                'Halaman ini menampilkan referensi tingkat breathability produk, dari sirkulasi udara rendah sampai sangat tinggi.',
                'Periksa level, label, dan deskripsinya sebelum mengubah data agar informasi breathability pada produk tetap konsisten dan mudah dipahami.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari breathability...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Level</th>
                                <th>label</th>
                                <th>Description</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @include('Admin.Breathability.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3" id="paginationWrapper">
                    {{ $breathabilities->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
