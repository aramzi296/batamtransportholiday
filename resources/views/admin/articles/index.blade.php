@extends('layouts.admin')

@section('title', 'Kelola Artikel')
@section('page-title', 'Kelola Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Daftar Artikel</h4>
        <p class="text-muted">Kelola artikel dan konten website</p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tulis Artikel Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter & Search -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.articles.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Artikel</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Judul atau konten...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Articles List -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Artikel</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                    <tr>
                        <td>
                            <div class="d-flex align-items-start">
                                @if($article->featured_image)
                                    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-newspaper text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $article->title }}</h6>
                                    <p class="text-muted mb-0 small">{{ Str::limit($article->excerpt, 100) }}</p>
                                    @if($article->is_featured)
                                        <span class="badge bg-warning text-dark mt-1">
                                            <i class="fas fa-star"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $article->category->name }}</span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $article->author->name }}</strong>
                            </div>
                        </td>
                        <td>
                            @if($article->status == 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($article->status == 'published')
                                <span class="badge bg-success">Published</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <small class="text-muted">Dibuat:</small><br>
                                {{ $article->created_at->format('d/m/Y H:i') }}
                            </div>
                            @if($article->published_at)
                                <div class="mt-1">
                                    <small class="text-muted">Published:</small><br>
                                    {{ $article->published_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.articles.show', $article) }}" 
                                   class="btn btn-sm btn-outline-info" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada artikel</p>
                            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tulis Artikel Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($articles->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-newspaper fa-2x mb-2"></i>
                <h4>{{ $articles->total() }}</h4>
                <p class="mb-0">Total Artikel</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h4>{{ $articles->where('status', 'published')->count() }}</h4>
                <p class="mb-0">Published</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-edit fa-2x mb-2"></i>
                <h4>{{ $articles->where('status', 'draft')->count() }}</h4>
                <p class="mb-0">Draft</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x mb-2"></i>
                <h4>{{ $articles->where('is_featured', true)->count() }}</h4>
                <p class="mb-0">Featured</p>
            </div>
        </div>
    </div>
</div>
@endsection