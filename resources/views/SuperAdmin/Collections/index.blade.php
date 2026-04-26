@extends('SuperAdmin.Template.index')
@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Collections</h4>
            <a href="{{ route('superadmin.collections.create') }}" class="btn btn-primary btn-sm">
                + Add
            </a>
        </div>

        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search category...">
        </div>

        <div id="category-table">
            @include('superadmin.collections.partials.table', ['collections' => $collections])
        </div>

    </div>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let search = this.value;

            fetch(`{{ route('superadmin.collections.index') }}?search=` + search, {
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
