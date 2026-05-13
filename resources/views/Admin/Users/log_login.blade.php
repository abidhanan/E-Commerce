@extends('Admin.Template.index')

@section('title', 'Log Login')

@section('content')

    <div class="container-fluid">

        <h4 class="mb-4">Log Login User</h4>

        <div class="card p-3">

            <input type="text" id="search" class="form-control mb-3" placeholder="Cari user, email, device, IP...">

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Device</th>
                            <th>Platform</th>
                            <th>Browser</th>
                            <th>IP</th>
                            <th>Login At</th>
                        </tr>
                    </thead>
                    <tbody id="logTable">
                        @include('Admin.Users.partials.log_table')
                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection
@push('scripts')
    <script>
        document.getElementById('search').addEventListener('keyup', function() {

            let value = this.value;

            fetch("{{ route('admin.users.loglogin') }}?search=" + value, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('logTable').innerHTML = data;
                });

        });
    </script>
@endpush
