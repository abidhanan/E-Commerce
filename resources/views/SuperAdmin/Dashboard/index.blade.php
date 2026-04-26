@extends('SuperAdmin.Template.index')

@section('title', 'Dashboard Super Admin')

@section('content')

    <div class="row g-4 mb-4">

        {{-- Total Users --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-2">
                <div class="card-header">
                    <h6 class="mb-0">Total Users</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-secondary">Users Terdaftar</small>
                        <h4 class="mt-2 mb-0">{{ $totalUsers }}</h4>
                    </div>
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-2">
                <div class="card-header">
                    <h6 class="mb-0">Revenue</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-secondary">Pendapatan</small>
                        <h4 class="mt-2 mb-0">$12,430</h4>
                    </div>
                    <i class="bi bi-currency-dollar fs-2"></i>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-2">
                <div class="card-header">
                    <h6 class="mb-0">Orders</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-secondary">Total Pesanan</small>
                        <h4 class="mt-2 mb-0">320</h4>
                    </div>
                    <i class="bi bi-bag-fill fs-2"></i>
                </div>
            </div>
        </div>

        {{-- Active --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card p-2">
                <div class="card-header">
                    <h6 class="mb-0">Active</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-secondary">User Aktif</small>
                        <h4 class="mt-2 mb-0">89</h4>
                    </div>
                    <i class="bi bi-activity fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-3">
        <h6 class="mb-3">User List</h6>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allUser as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->first()->name ?? 'No Role' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




@endsection
