@extends('Admin.Template.index')
@section('title', 'Crash Replacement Form')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">{{ isset($crashReplacement) ? 'Edit Crash Replacement' : 'Tambah Crash Replacement' }}</h4>

        <form
            action="{{ isset($crashReplacement) ? route('admin.crash-replacements.update', $crashReplacement->id) : route('admin.crash-replacements.store') }}"
            method="POST">
            @csrf
            @if (isset($crashReplacement))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Pertanyaan</label>
                <input type="text" name="question" class="form-control"
                    value="{{ old('question', $crashReplacement->question ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Jawaban</label>
                <textarea name="answer" class="form-control" rows="5" required>{{ old('answer', $crashReplacement->answer ?? '') }}</textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1"
                    {{ old('is_active', isset($crashReplacement) ? (int) $crashReplacement->is_active : 1) ? 'checked' : '' }}>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">
                {{ isset($crashReplacement) ? 'Update' : 'Simpan' }}
            </button>

        </form>

    </div>
@endsection
