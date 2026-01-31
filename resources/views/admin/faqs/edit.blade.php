@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page-title', 'Edit FAQ')

@section('content')
<!-- Quill Rich Text Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Edit FAQ</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" id="faqForm">
            @csrf
            @method('PUT')
            
            <!-- Language Tabs -->
            <ul class="nav nav-tabs mb-4" id="langTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="id-tab" data-bs-toggle="tab" data-bs-target="#id-pane" type="button" role="tab">
                        <i class="fas fa-flag"></i> Bahasa Indonesia
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-pane" type="button" role="tab">
                        <i class="fas fa-flag"></i> English
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="langTabContent">
                <!-- Indonesian Tab -->
                <div class="tab-pane fade show active" id="id-pane" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan (Bahasa Indonesia) *</label>
                        <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" 
                               value="{{ old('question', $faq->question) }}" required placeholder="Masukkan pertanyaan">
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jawaban (Bahasa Indonesia) *</label>
                        <div id="answer-editor-id" style="height: 300px;"></div>
                        <textarea name="answer" id="answer-hidden-id" class="d-none" required>{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- English Tab -->
                <div class="tab-pane fade" id="en-pane" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Question (English) <small class="text-muted">(Optional)</small></label>
                        <input type="text" name="question_en" class="form-control @error('question_en') is-invalid @enderror" 
                               value="{{ old('question_en', $faq->question_en) }}" placeholder="Enter question">
                        @error('question_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Answer (English) <small class="text-muted">(Optional)</small></label>
                        <div id="answer-editor-en" style="height: 300px;"></div>
                        <textarea name="answer_en" id="answer-hidden-en" class="d-none">{{ old('answer_en', $faq->answer_en) }}</textarea>
                        @error('answer_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                               value="{{ old('sort_order', $faq->sort_order) }}" min="0" placeholder="0">
                        <small class="form-text text-muted">Urutan tampil (angka lebih kecil akan muncul lebih dulu)</small>
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                FAQ Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-warning" onclick="updateHiddenInputs(); return true;">
                    <i class="fas fa-save"></i> Update FAQ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quill Rich Text Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

@push('scripts')
<script>
// Initialize Quill Editor for Indonesian
const quillId = new Quill('#answer-editor-id', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['link'],
            [{ 'color': [] }, { 'background': [] }],
            ['clean']
        ]
    }
});

// Initialize Quill Editor for English
const quillEn = new Quill('#answer-editor-en', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['link'],
            [{ 'color': [] }, { 'background': [] }],
            ['clean']
        ]
    }
});

// Set initial content
quillId.root.innerHTML = {!! json_encode(old('answer', $faq->answer)) !!};
@if($faq->answer_en)
    quillEn.root.innerHTML = {!! json_encode(old('answer_en', $faq->answer_en)) !!};
@endif

// Update hidden inputs on text change
quillId.on('text-change', function() {
    document.getElementById('answer-hidden-id').value = quillId.root.innerHTML;
});

quillEn.on('text-change', function() {
    document.getElementById('answer-hidden-en').value = quillEn.root.innerHTML;
});

function updateHiddenInputs() {
    document.getElementById('answer-hidden-id').value = quillId.root.innerHTML;
    document.getElementById('answer-hidden-en').value = quillEn.root.innerHTML;
}

// Update hidden inputs before form submit
document.getElementById('faqForm').addEventListener('submit', function(e) {
    updateHiddenInputs();
});
</script>
@endpush
@endsection
