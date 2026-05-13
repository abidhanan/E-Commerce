@extends('Admin.Template.index')

@section('title', 'Tambah User')

@section('content')
    <div class="container" style="max-width:900px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <h5 class="mb-4 fw-semibold">Tambah User</h5>

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}">
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
