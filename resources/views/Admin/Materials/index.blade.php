@extends('Admin.Template.index')
@section('content')
    <div class="container py-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Data Material',
            'createRoute' => route('admin.materials.create'),
            'createLabel' => '+ Tambah Material',
            'createClass' => 'btn btn-success',
            'collapseId' => 'materialsInfoCollapse',
            'helpTitle' => 'Keterangan Material',
            'messages' => [
                'Data material digunakan untuk menentukan bahan penyusun produk, lengkap dengan gambar pendukung dan deskripsinya.',
                'Pastikan nama material, ilustrasi, dan penjelasannya akurat karena data ini akan dipilih langsung pada form produk.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari material...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Images</th>
                                <th>Material</th>
                                <th>Description</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @include('Admin.Materials.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3" id="paginationWrapper">
                    {{ $materials->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>

    </div>
@endsection
