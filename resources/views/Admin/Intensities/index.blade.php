@extends('Admin.Template.index')

@section('content')
    <div class="container py-4">

        @include('Admin.partials.index-help-header', [
            'heading' => 'Data Intensities',
            'createRoute' => route('admin.intensities.create'),
            'createLabel' => '+ Tambah Intensities',
            'createClass' => 'btn btn-success',
            'collapseId' => 'intensitiesInfoCollapse',
            'helpTitle' => 'Keterangan Intensities',
            'messages' => [
                'Halaman ini menyimpan referensi intensitas penggunaan produk, misalnya untuk aktivitas ringan atau aktivitas yang lebih tinggi.',
                'Gunakan daftar ini untuk menjaga agar pilihan intensity di form produk tetap jelas dan seragam di seluruh katalog.',
            ],
        ])

        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SEARCH REALTIME --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari intensities...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>label</th>
                                <th>Description</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable">
                            @include('Admin.Intensities.partials.table')
                        </tbody>
                    </table>
                </div>

                <div class="mt-3" id="paginationWrapper">
                    {{ $intensities->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
