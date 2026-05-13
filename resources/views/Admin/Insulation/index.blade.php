@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Data Insulation',
            'createRoute' => route('admin.insulations.create'),
            'createLabel' => '+ Tambah Insulation',
            'createClass' => 'btn btn-success',
            'collapseId' => 'insulationInfoCollapse',
            'helpTitle' => 'Keterangan Insulation',
            'messages' => [
                'Data ini dipakai sebagai referensi tingkat insulasi produk, dari level rendah sampai tinggi untuk kebutuhan cuaca yang berbeda.',
                'Periksa level, label, dan deskripsinya sebelum mengubah data agar informasi insulation pada produk tetap konsisten.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari insulation...">
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
                            @include('Admin.Insulation.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3" id="paginationWrapper">
                    {{ $insulations->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
