@extends('Admin.Template.index')
@section('title', 'FAQ Form')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">{{ isset($faq) ? 'Edit FAQ' : 'Tambah FAQ' }}</h4>

        <form action="{{ isset($faq) ? route('admin.faqs.update', $faq->id) : route('admin.faqs.store') }}" method="POST">
            @csrf
            @if (isset($faq))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Pertanyaan</label>
                <input type="text" name="question" class="form-control"
                    value="{{ old('question', $faq->question ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label>Jawaban</label>
                <textarea name="answer" class="form-control" rows="5" required>{{ old('answer', $faq->answer ?? '') }}</textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" class="form-check-input" value="1"
                    {{ old('is_active', isset($faq) ? (int) $faq->is_active : 1) ? 'checked' : '' }}>
                <label class="form-check-label">Aktif</label>
            </div>

            <button class="btn btn-primary">
                {{ isset($faq) ? 'Update' : 'Simpan' }}
            </button>

        </form>

    </div>
@endsection
