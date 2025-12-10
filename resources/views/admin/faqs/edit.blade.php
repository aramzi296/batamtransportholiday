@extends('layouts.admin')

@section('title', 'Edit FAQ')
@section('page-title', 'Edit FAQ')

@section('content')
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

        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Pertanyaan *</label>
                <input type="text" name="question" class="form-control @error('question') is-invalid @enderror" 
                       value="{{ old('question', $faq->question) }}" required placeholder="Masukkan pertanyaan">
                @error('question')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jawaban *</label>
                <div class="rich-text-editor">
                    <div class="editor-toolbar mb-2 border rounded p-2 bg-light">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold')" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic')" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline')" title="Underline">
                            <i class="fas fa-underline"></i>
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatList('insertUnorderedList')" title="Bullet List">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatList('insertOrderedList')" title="Numbered List">
                                <i class="fas fa-list-ol"></i>
                            </button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatIndent('outdent')" title="Decrease Indent">
                                <i class="fas fa-outdent"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatIndent('indent')" title="Increase Indent">
                                <i class="fas fa-indent"></i>
                            </button>
                        </div>
                    </div>
                    <div id="answer-editor" 
                         contenteditable="true" 
                         class="form-control @error('answer') is-invalid @enderror" 
                         style="min-height: 200px; padding: 10px; border: 1px solid #ced4da; border-radius: 0.375rem;"
                         oninput="updateHiddenInput()">{!! old('answer', $faq->answer) !!}</div>
                    <textarea name="answer" id="answer-hidden" class="d-none" required>{{ old('answer', $faq->answer) }}</textarea>
                </div>
                @error('answer')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Gunakan toolbar di atas untuk memformat teks</small>
            </div>

            <div class="row">
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
                <button type="submit" class="btn btn-warning" onclick="updateHiddenInput(); return true;">
                    <i class="fas fa-save"></i> Update FAQ
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function formatText(command) {
        document.execCommand(command, false, null);
        updateHiddenInput();
    }

    function formatList(command) {
        document.execCommand(command, false, null);
        updateHiddenInput();
    }

    function formatIndent(command) {
        document.execCommand(command, false, null);
        updateHiddenInput();
    }

    function updateHiddenInput() {
        const editor = document.getElementById('answer-editor');
        const hiddenInput = document.getElementById('answer-hidden');
        hiddenInput.value = editor.innerHTML;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateHiddenInput();
    });
</script>
@endpush
@endsection







