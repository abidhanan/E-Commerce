@extends('Admin.Template.index')
@section('title', 'Size Guides')

@push('css')
@endpush

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-3">
            <h4>Size Guides</h4>
            <a href="{{ route('admin.size-guides.create') }}" class="btn btn-primary">
                + Add Size Guide
            </a>
        </div>

        {{-- 🔍 Search --}}
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search type or name...">
        </div>

        {{-- 📦 Table --}}
        <div id="table-data">
            @include('Admin.SizeGuides.partials.table')
        </div>

        {{-- 📄 Pagination --}}
        <div id="pagination-data">
            {{ $sizeGuides->links() }}
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let search = document.getElementById('search');

        function fetchData(page = 1) {
            let query = search.value;

            fetch(`?page=${page}&search=${query}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('table-data').innerHTML = data.table;
                    document.getElementById('pagination-data').innerHTML = data.pagination;
                });
        }

        // 🔍 realtime search
        search.addEventListener('keyup', function() {
            fetchData();
        });

        // 📄 pagination click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                let url = new URL(e.target.href);
                let page = url.searchParams.get('page');
                fetchData(page);
            }
        });
    </script>
@endpush
