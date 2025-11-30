@extends('layouts.app')

@section('title', 'Artikel & Tips')

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Artikel & Tips Berkendara</h1>
                <p class="lead mb-4">Temukan tips berguna, panduan berkendara, dan informasi terkini tentang dunia otomotif</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-newspaper fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('articles.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Cari Artikel</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Kategori</label>
                                <select name="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" 
                                                {{ request('category') === $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Cari
                                    </button>
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($articles->count() > 0)
        <!-- Articles Grid -->
        <div class="row g-4">
            @foreach($articles as $article)
            <div class="col-lg-4 col-md-6">
                <article class="card article-card h-100 border-0 shadow-sm">
                    <!-- Featured Image -->
                    <div class="article-image-wrapper">
                        <img src="{{ $article->featured_image ?: 'https://via.placeholder.com/400x250/6c757d/ffffff?text=No+Image' }}" 
                             class="card-img-top article-image" alt="{{ $article->title }}">
                        <div class="article-overlay">
                            <span class="badge bg-primary article-category">
                                <i class="fas fa-tag me-1"></i>{{ $article->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <!-- Article Meta -->
                        <div class="article-meta mb-2">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>{{ $article->published_at->format('d M Y') }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-user me-1"></i>{{ $article->author->name }}
                            </small>
                        </div>
                        
                        <!-- Title -->
                        <h5 class="card-title article-title mb-3">
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                                {{ $article->title }}
                            </a>
                        </h5>
                        
                        <!-- Excerpt -->
                        <p class="card-text text-muted flex-grow-1">{!! \Illuminate\Support\Str::limit(strip_tags($article->excerpt), 150) !!}</p>
                        
                        <!-- Read More -->
                        <div class="mt-auto">
                            <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-right me-2"></i>Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-4"></i>
                    @if(request('search') || request('category'))
                        <h4>Tidak Ada Artikel yang Ditemukan</h4>
                        <p class="text-muted mb-4">Coba ubah kata kunci atau filter kategori untuk hasil yang berbeda</p>
                        <a href="{{ route('articles.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh me-2"></i>Lihat Semua Artikel
                        </a>
                    @else
                        <h4>Belum Ada Artikel</h4>
                        <p class="text-muted mb-4">Artikel sedang dalam proses penulisan. Silakan kembali lagi nanti</p>
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Kembali ke Home
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Popular Categories -->
    @if($categories->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-tags me-2 text-primary"></i>Kategori Populer
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $category)
                            <a href="{{ route('articles.index', ['category' => $category->slug]) }}" 
                               class="btn btn-outline-primary btn-sm category-tag">
                                <i class="fas fa-tag me-1"></i>{{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .article-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .article-image-wrapper {
        position: relative;
        overflow: hidden;
    }
    
    .article-image {
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .article-card:hover .article-image {
        transform: scale(1.05);
    }
    
    .article-overlay {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    
    .article-category {
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .article-meta {
        font-size: 0.8rem;
    }
    
    .article-title a {
        transition: color 0.3s ease;
    }
    
    .article-title a:hover {
        color: var(--bs-primary) !important;
    }
    
    .category-tag {
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    
    .category-tag:hover {
        transform: translateY(-2px);
    }
    
    /* Hero background */
    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Form styling */
    .form-control, .form-select {
        border-radius: 8px;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .input-group-text {
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }
    
    .btn {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    /* Card styling */
    .card {
        border-radius: 16px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .article-image {
            height: 200px;
        }
    }
</style>
@endpush