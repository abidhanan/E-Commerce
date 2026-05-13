@extends('Admin.Template.index')
@section('title', isset($displayLogin) ? 'Edit Banner Login' : 'Tambah Banner Login')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h4 class="mb-1">
                {{ isset($displayLogin) ? 'Edit Banner Login' : 'Tambah Banner Login' }}
            </h4>

            <p class="text-muted mb-0">
                Kelola banner visual yang akan ditampilkan pada halaman login pengguna.
            </p>
        </div>

        {{-- Form Card --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                <form
                    action="{{ isset($displayLogin) ? route('admin.display-logins.update', $displayLogin->id) : route('admin.display-logins.store') }}"
                    method="POST" enctype="multipart/form-data">

                    @csrf

                    @if (isset($displayLogin))
                        @method('PUT')
                    @endif

                    {{-- Banner Name --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Nama Banner
                        </label>

                        <input type="text" name="label" class="form-control" placeholder="Contoh: Banner Promo Login"
                            value="{{ old('label', $displayLogin->label ?? '') }}" required>

                        <small class="text-muted">
                            Nama internal untuk mempermudah identifikasi banner.
                        </small>
                    </div>

                    {{-- Upload Image --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Gambar Banner
                        </label>

                        <input type="file" name="image_path" class="form-control" accept="image/*"
                            {{ isset($displayLogin) ? '' : 'required' }}>

                        <small class="text-muted">
                            Upload gambar banner yang akan tampil di halaman login.
                        </small>
                    </div>

                    {{-- Current Image Preview --}}
                    @if (isset($displayLogin) && $displayLogin->image_path)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                Gambar Saat Ini
                            </label>

                            <div>
                                <img src="{{ asset('storage/' . $displayLogin->image_path) }}" alt="Current Banner"
                                    class="img-fluid rounded border" style="max-height: 250px;">
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ isset($displayLogin) ? 'Update Banner' : 'Simpan Banner' }}
                        </button>

                        <a href="{{ route('admin.display-logins.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
