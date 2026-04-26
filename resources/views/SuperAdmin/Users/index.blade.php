@extends('SuperAdmin.Template.index')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">User Management</h4>
            <a href="{{ route('SuperAdmin.Users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Tambah User
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="mb-3">
                    <input type="text" id="search" class="form-control" placeholder="Cari nama, email, role...">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="userTable">
                            @include('Admin.Users.partials.user_table')
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('search').addEventListener('keyup', function() {

            let value = this.value;

            fetch("{{ route('admin.users.index') }}?search=" + value, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('userTable').innerHTML = data;
                });

        });
    </script>
@endpush
