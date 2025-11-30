@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')
@section('page-title', 'Tulis Artikel Baru')

@push('styles')
<!-- Quill Rich Text Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor {
        min-height: 200px;
        max-height: 400px;
    }
    .image-preview {
        max-width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }
    .tags-input {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        min-height: 45px;
    }
    .tag-item {
        background: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .tag-remove {
        cursor: pointer;
        background: none;
        border: none;
        color: white;
        font-size: 14px;
        padding: 0;
        margin: 0;
        line-height: 1;
    }
    .tag-input {
        border: none;
        outline: none;
        flex: 1;
        min-width: 100px;
        background: transparent;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Tulis Artikel Baru</h4>
        <p class="text-muted">Buat konten artikel dengan rich text editor</p>
    </div>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.articles.store') }}" method="POST" id="articleForm">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Konten Artikel</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required
                               placeholder="Masukkan judul artikel yang menarik...">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Ringkasan Artikel</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="3" 
                                  placeholder="Ringkasan singkat artikel (opsional, akan di-generate otomatis jika kosong)">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maksimal 500 karakter. Jika kosong, akan diambil dari 200 karakter pertama konten.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten Artikel *</label>
                        <div id="quill-editor" style="height: 300px;"></div>
                        <input type="hidden" name="content" id="content" value="{{ old('content') }}">
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <div class="tags-input" id="tagsContainer">
                            <input type="text" class="tag-input" id="tagInput" 
                                   placeholder="Ketik tag dan tekan Enter...">
                        </div>
                        <div id="tagsInputsContainer">
                            @if(old('tags'))
                                @foreach(old('tags') as $tag)
                                    <input type="hidden" name="tags[]" value="{{ $tag }}">
                                @endforeach
                            @endif
                        </div>
                        <div class="form-text">Tekan Enter untuk menambah tag. Gunakan tag untuk membantu kategorisasi artikel.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Publish Options -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Pengaturan Publish</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                Draft (Simpan sebagai draft)
                            </option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                Published (Terbitkan sekarang)
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" 
                               name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <i class="fas fa-star text-warning"></i> Artikel Unggulan
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Artikel
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewArticle()">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Featured Image -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Gambar Utama</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">URL Gambar</label>
                        <input type="url" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" value="{{ old('featured_image') }}"
                               placeholder="https://example.com/image.jpg"
                               onchange="previewImage(this.value)">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Gunakan URL gambar dari Unsplash atau sumber gambar gratis lainnya.</div>
                    </div>
                    
                    <div id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" class="image-preview">
                    </div>
                    
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="generateUnsplashImage()">
                            <i class="fas fa-random"></i> Generate dari Unsplash
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<!-- Quill Rich Text Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// Initialize Quill Editor
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            ['clean']
        ]
    },
    placeholder: 'Tulis konten artikel Anda di sini...'
});

// Set initial content if editing
@if(old('content'))
    quill.root.innerHTML = {!! json_encode(old('content')) !!};
@endif

// Update hidden input on content change
quill.on('text-change', function() {
    document.getElementById('content').value = quill.root.innerHTML;
});

// Form submission handler
document.getElementById('articleForm').addEventListener('submit', function(e) {
    // Update content before submit
    document.getElementById('content').value = quill.root.innerHTML;
    
    // Validate content is not empty
    if (quill.getText().trim().length === 0) {
        e.preventDefault();
        alert('Konten artikel tidak boleh kosong!');
        return false;
    }
});

// Tags Management
let tags = [];
const tagInput = document.getElementById('tagInput');
const tagsInputsContainer = document.getElementById('tagsInputsContainer');

// Load existing tags from existing inputs
const existingTagInputs = document.querySelectorAll('input[name="tags[]"]');
existingTagInputs.forEach(input => {
    if (input.value) {
        tags.push(input.value);
    }
});
if (tags.length > 0) {
    renderTags();
}

tagInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addTag();
    }
});

function addTag() {
    const tagValue = tagInput.value.trim();
    if (tagValue && !tags.includes(tagValue)) {
        tags.push(tagValue);
        tagInput.value = '';
        renderTags();
        updateTagsHidden();
    }
}

function removeTag(tag) {
    tags = tags.filter(t => t !== tag);
    renderTags();
    updateTagsHidden();
}

function renderTags() {
    const container = document.getElementById('tagsContainer');
    const tagElements = tags.map(tag => `
        <span class="tag-item">
            ${tag}
            <button type="button" class="tag-remove" onclick="removeTag('${tag}')">×</button>
        </span>
    `).join('');
    
    container.innerHTML = tagElements + '<input type="text" class="tag-input" id="tagInput" placeholder="Ketik tag dan tekan Enter...">';
    
    // Re-bind event listener to new input
    const newTagInput = document.getElementById('tagInput');
    newTagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            addTag();
        }
    });
}

function updateTagsHidden() {
    // Clear existing inputs
    tagsInputsContainer.innerHTML = '';
    
    // Create new hidden inputs for each tag
    tags.forEach(tag => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = tag;
        tagsInputsContainer.appendChild(input);
    });
}

// Image Preview
function previewImage(url) {
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('previewImg');
    
    if (url) {
        img.src = url;
        preview.style.display = 'block';
        
        img.onerror = function() {
            preview.style.display = 'none';
            alert('Gagal memuat gambar. Pastikan URL valid.');
        };
    } else {
        preview.style.display = 'none';
    }
}

// Generate Unsplash Image
function generateUnsplashImage() {
    const keywords = ['article', 'blog', 'writing', 'content', 'news', 'information', 'reading', 'book'];
    const randomKeyword = keywords[Math.floor(Math.random() * keywords.length)];
    const unsplashUrl = `https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=400&fit=crop&crop=entropy&cs=tinysrgb&q=80`;
    
    document.getElementById('featured_image').value = unsplashUrl;
    previewImage(unsplashUrl);
}

// Preview Article
function previewArticle() {
    const title = document.getElementById('title').value;
    const content = quill.root.innerHTML;
    
    if (!title || !content) {
        alert('Harap isi judul dan konten terlebih dahulu.');
        return;
    }
    
    const previewWindow = window.open('', '_blank');
    previewWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${title}</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .article-content { line-height: 1.8; }
                .article-content img { max-width: 100%; height: auto; }
            </style>
        </head>
        <body>
            <div class="container my-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h1 class="mb-4">${title}</h1>
                        <div class="article-content">
                            ${content}
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
    `);
    previewWindow.document.close();
}
</script>
@endpush
@endsection