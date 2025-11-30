@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Artikel</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles.index', ['category' => $article->category->slug]) }}">{{ $article->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Main Article Content -->
        <div class="col-lg-8">
            <!-- Article Header -->
            <article class="card border-0 shadow-sm mb-4">
                <!-- Featured Image -->
                @if($article->featured_image)
                <div class="article-hero">
                    <img src="{{ $article->featured_image }}" class="card-img-top article-featured-image" alt="{{ $article->title }}">
                    <div class="article-hero-overlay">
                        <span class="badge bg-primary article-category-badge">
                            <i class="fas fa-tag me-2"></i>{{ $article->category->name }}
                        </span>
                    </div>
                </div>
                @endif
                
                <div class="card-body p-4 p-md-5">
                    <!-- Article Meta -->
                    <div class="article-meta mb-4">
                        <div class="d-flex flex-wrap align-items-center gap-3 text-muted">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar me-2"></i>
                                <span>{{ $article->published_at->format('d F Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user me-2"></i>
                                <span>{{ $article->author->name }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2"></i>
                                <span>{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min baca</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Article Title -->
                    <h1 class="article-title mb-4">{{ $article->title }}</h1>
                    
                    <!-- Article Excerpt -->
                    @if($article->excerpt)
                    <div class="article-excerpt mb-4">
                        <p class="lead text-muted">{{ $article->excerpt }}</p>
                    </div>
                    @endif
                    
                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                    
                    <!-- Article Footer -->
                    <div class="article-footer mt-5 pt-4 border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="author-avatar me-3">
                                        <div class="avatar-circle">
                                            {{ strtoupper(substr($article->author->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $article->author->name }}</h6>
                                        <small class="text-muted">Penulis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="article-share">
                                    <span class="me-2 text-muted">Bagikan:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                                       class="btn btn-outline-primary btn-sm me-1" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($article->title) }}" 
                                       class="btn btn-outline-info btn-sm me-1" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . request()->fullUrl()) }}" 
                                       class="btn btn-outline-success btn-sm" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-newspaper me-2 text-primary"></i>Artikel Terkait
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($relatedArticles as $relatedArticle)
                    <div class="related-article-item">
                        <div class="row g-3 align-items-center">
                            <div class="col-4">
                                <img src="{{ $relatedArticle->featured_image ?: 'https://via.placeholder.com/150x100/6c757d/ffffff?text=No+Image' }}" 
                                     class="img-fluid rounded related-article-image" alt="{{ $relatedArticle->title }}">
                            </div>
                            <div class="col-8">
                                <h6 class="mb-1">
                                    <a href="{{ route('articles.show', $relatedArticle->slug) }}" 
                                       class="text-decoration-none text-dark related-article-title">
                                        {{ \Illuminate\Support\Str::limit($relatedArticle->title, 60) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>{{ $relatedArticle->published_at->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-tags me-2 text-primary"></i>Kategori Artikel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary btn-sm text-start">
                            <i class="fas fa-list me-2"></i>Semua Artikel
                        </a>
                        @foreach(\App\Models\ArticleCategory::all() as $category)
                            <a href="{{ route('articles.index', ['category' => $category->slug]) }}" 
                               class="btn {{ $category->id === $article->category_id ? 'btn-primary' : 'btn-outline-primary' }} btn-sm text-start">
                                <i class="fas fa-tag me-2"></i>{{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body text-center p-4">
                    <i class="fas fa-envelope fa-2x mb-3"></i>
                    <h5 class="mb-3">Dapatkan Artikel Terbaru</h5>
                    <p class="mb-3">Berlangganan newsletter kami untuk mendapatkan tips dan artikel terbaru langsung ke email Anda</p>
                    <form>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email Anda" required>
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                    <small class="opacity-75">*Kami tidak akan mengirim spam</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Artikel
                </a>
                <a href="{{ route('articles.index', ['category' => $article->category->slug]) }}" class="btn btn-primary">
                    <i class="fas fa-tag me-2"></i>Artikel {{ $article->category->name }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-hero {
        position: relative;
        overflow: hidden;
    }
    
    .article-featured-image {
        height: 400px;
        object-fit: cover;
        width: 100%;
    }
    
    .article-hero-overlay {
        position: absolute;
        top: 20px;
        right: 20px;
    }
    
    .article-category-badge {
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .article-meta {
        font-size: 0.9rem;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
    }
    
    .article-title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #2c3e50;
    }
    
    .article-excerpt {
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    .article-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #495057;
    }
    
    .article-content p {
        margin-bottom: 1.5rem;
    }
    
    .article-content h1,
    .article-content h2,
    .article-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #2c3e50;
        font-weight: 600;
    }
    
    .article-content h1 { font-size: 2rem; }
    .article-content h2 { font-size: 1.5rem; }
    .article-content h3 { font-size: 1.25rem; }
    
    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 2rem;
    }
    
    .article-content li {
        margin-bottom: 0.5rem;
    }
    
    .article-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6c757d;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
    }
    
    .article-content code {
        background: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9rem;
        color: #e83e8c;
    }
    
    .article-content pre {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1.5rem 0;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    
    .article-content a {
        color: #667eea;
        text-decoration: underline;
    }
    
    .article-content a:hover {
        color: #5a67d8;
    }
    
    .article-content strong {
        font-weight: 600;
    }
    
    .article-content em {
        font-style: italic;
    }
    
    /* Quill editor specific classes */
    .article-content .ql-align-center {
        text-align: center;
    }
    
    .article-content .ql-align-right {
        text-align: right;
    }
    
    .article-content .ql-align-justify {
        text-align: justify;
    }
    
    .article-content .ql-indent-1 {
        padding-left: 3em;
    }
    
    .article-content .ql-indent-2 {
        padding-left: 6em;
    }
    
    .article-content .ql-indent-3 {
        padding-left: 9em;
    }
    
    .author-avatar .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .related-article-item {
        padding: 1rem;
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.3s ease;
    }
    
    .related-article-item:hover {
        background-color: #f8f9fa;
    }
    
    .related-article-item:last-child {
        border-bottom: none;
    }
    
    .related-article-image {
        height: 60px;
        object-fit: cover;
    }
    
    .related-article-title {
        transition: color 0.3s ease;
    }
    
    .related-article-title:hover {
        color: var(--bs-primary) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card {
        border-radius: 16px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .article-share .btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .article-title {
            font-size: 2rem;
        }
        
        .article-featured-image {
            height: 250px;
        }
        
        .article-hero-overlay {
            top: 15px;
            right: 15px;
        }
    }
</style>
@endpush