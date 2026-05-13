@extends('Admin.Template.index')
@section('title', isset($socialLink) && $socialLink->exists ? 'Edit Social Link' : 'Tambah Social Link')

@section('content')
    <div class="container-fluid">
        <div class="admin-page-header">
            <div>
                <span class="admin-page-eyebrow">Displays</span>
                <h1 class="admin-page-title">{{ isset($socialLink) && $socialLink->exists ? 'Edit Link Sosial' : 'Tambah Link Sosial' }}</h1>
                <p class="admin-page-subtitle">Masukkan link sosial media atau official store. Untuk kategori official store, pilihan platform dibatasi ke Shopee, Tokopedia, Zalora, dan TikTok Shop.</p>
            </div>

            <div class="admin-page-actions">
                <a href="{{ route('admin.social-links.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <form action="{{ isset($socialLink) && $socialLink->exists ? route('admin.social-links.update', $socialLink) : route('admin.social-links.store') }}"
            method="POST">
            @csrf
            @if (isset($socialLink) && $socialLink->exists)
                @method('PUT')
            @endif

            <div class="card p-3 p-lg-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="type" class="form-label">Kategori</label>
                        <select name="type" id="type" class="form-select" required>
                            @foreach ($typeOptions as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('type', $socialLink->type ?: \App\Models\SocialLink::TYPE_SOCIAL) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="platform" class="form-label">Platform</label>
                        <select name="platform" id="platform" class="form-select" required></select>
                    </div>

                    <div class="col-md-6">
                        <label for="label" class="form-label">Nama Tampil</label>
                        <input type="text" name="label" id="label" class="form-control"
                            value="{{ old('label', $socialLink->label) }}"
                            placeholder="Contoh: Instagram Official">
                        <small class="text-muted">Kosongkan jika ingin pakai nama default dari platform.</small>
                    </div>

                    <div class="col-md-6">
                        <label for="url" class="form-label">URL</label>
                        <input type="text" name="url" id="url" class="form-control"
                            value="{{ old('url', $socialLink->url) }}"
                            placeholder="https://instagram.com/namatoko" required>
                    </div>

                    <div class="col-md-4">
                        <label for="position" class="form-label">Posisi Urutan</label>
                        <input type="number" name="position" id="position" class="form-control" min="0"
                            value="{{ old('position', $socialLink->position) }}" placeholder="0">
                    </div>

                    <div class="col-md-8 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                                {{ old('is_active', $socialLink->exists ? (int) $socialLink->is_active : 1) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Aktifkan link ini di storefront</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">Link aktif akan otomatis muncul di footer storefront sesuai kategorinya.</p>
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($socialLink) && $socialLink->exists ? 'Update Link' : 'Simpan Link' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const platformOptions = @json($platformOptions);
        const initialPlatform = @json(old('platform', $socialLink->platform));
        const typeSelect = document.getElementById('type');
        const platformSelect = document.getElementById('platform');
        const labelInput = document.getElementById('label');

        function getPlatformLabel(type, platform) {
            return (platformOptions[type] || {})[platform] || '';
        }

        function rebuildPlatformOptions() {
            const type = typeSelect.value;
            const options = platformOptions[type] || {};
            const currentValue = platformSelect.dataset.selected || initialPlatform;

            platformSelect.innerHTML = Object.entries(options).map(([value, label]) => {
                const isSelected = currentValue === value ? 'selected' : '';
                return `<option value="${value}" ${isSelected}>${label}</option>`;
            }).join('');

            if (!platformSelect.value && Object.keys(options).length > 0) {
                platformSelect.value = Object.keys(options)[0];
            }
        }

        function autofillLabel() {
            if (labelInput.dataset.userEdited === 'true') {
                return;
            }

            const type = typeSelect.value;
            const platform = platformSelect.value;
            labelInput.value = getPlatformLabel(type, platform);
        }

        labelInput.addEventListener('input', function() {
            labelInput.dataset.userEdited = this.value.trim() !== '' ? 'true' : 'false';
        });

        typeSelect.addEventListener('change', function() {
            platformSelect.dataset.selected = '';
            labelInput.dataset.userEdited = 'false';
            rebuildPlatformOptions();
            autofillLabel();
        });

        platformSelect.addEventListener('change', function() {
            labelInput.dataset.userEdited = 'false';
            autofillLabel();
        });

        rebuildPlatformOptions();

        if (!labelInput.value.trim()) {
            autofillLabel();
        } else {
            labelInput.dataset.userEdited = 'true';
        }
    </script>
@endpush
