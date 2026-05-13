@extends('Admin.Template.index')

@section('title', 'Hak Akses Role')

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Master Akses</span>
                <h1 class="admin-page-title">Hak Akses Role</h1>
                <p class="admin-page-subtitle">Atur menu dan aksi yang bisa dipakai oleh setiap role dari satu halaman.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.role-access.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="min-width: 280px;">Akses</th>
                                @foreach ($roles as $role)
                                    <th class="text-center text-capitalize">{{ $role->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr class="table-light">
                                    <th colspan="{{ $roles->count() + 1 }}">{{ $group['label'] }}</th>
                                </tr>

                                @foreach ($group['permissions'] as $permission => $description)
                                    <tr>
                                        <td>
                                            <strong>{{ ucwords(str_replace(['manage ', 'view '], ['', 'Lihat '], $permission)) }}</strong>
                                            <div class="text-muted small">{{ $description }}</div>
                                            <code class="small">{{ $permission }}</code>
                                        </td>

                                        @foreach ($roles as $role)
                                            @php
                                                $isSuperadmin = $role->name === 'superadmin';
                                                $checked = $isSuperadmin || $role->hasPermissionTo($permission);
                                            @endphp
                                            <td class="text-center">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    name="permissions[{{ $role->name }}][]"
                                                    value="{{ $permission }}"
                                                    @checked($checked)
                                                    @disabled($isSuperadmin)>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-dark">Kembali</a>
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-shield-check"></i> Simpan Hak Akses
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
