@extends('Admin.Template.index')
@section('content')
    <div class="container mt-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Category</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>

                    @php
                        // Logika Mutlak: Bekukan JIKA slot >= 3 DAN kategori ini sebelumnya tidak dicentang
                        $isLocked = ($featuredCount >= 3 && !$category->is_featured_home);
                    @endphp

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_featured_home" name="is_featured_home" value="1" 
                            {{ old('is_featured_home', $category->is_featured_home) ? 'checked' : '' }} 
                            {{ $isLocked ? 'disabled' : '' }}>
                        
                        <label class="form-check-label fw-bold text-uppercase {{ $isLocked ? 'text-muted' : '' }}" style="font-size: 0.85rem; letter-spacing: 1px;" for="is_featured_home">
                            Tampilkan di 3 Kotak Utama Beranda
                        </label>

                        @if($isLocked)
                            <small class="text-danger d-block mt-1 fw-bold">
                                <i class="bi bi-lock-fill"></i> Slot Penuh (3/3). Tombol dikunci. Copot centang di kategori lain untuk menggunakan slot ini.
                            </small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- None --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image</label>

                        @if ($category->img)
                            <div class="mb-2 border rounded p-1 d-inline-block bg-light">
                                <img src="{{ asset('storage/' . $category->img) }}" style="height:80px; width:auto; border-radius:4px; object-fit:cover;">
                            </div>
                        @endif

                        <input type="file" name="img" class="form-control" accept="image/jpeg,image/png,image/jpg">

                        <small class="text-muted d-block mt-1">
                            Kosongkan jika tidak ingin mengganti gambar. Maksimal 2MB.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            Back
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