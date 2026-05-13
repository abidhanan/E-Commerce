@extends('Admin.Template.index')

@section('title', 'Edit User')

@section('content')

    <div class="container" style="max-width:900px;">
        <div class="card shadow border-1">
            <div class="card-body p-4">

                <h5 class="mb-4 fw-semibold">Edit User</h5>

                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Kosongkan jika tidak diganti">
                        <small class="text-muted">
                            Isi hanya jika ingin mengganti password
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
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
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
