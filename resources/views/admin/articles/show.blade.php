@extends('layouts.admin')

@section('title', 'Detail Artikel')
@section('page-title', 'Detail Artikel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>{{ $article->title }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Artikel</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <!-- Article Content -->
                    <div class="card mb-4">
                        <div class="card-body">
                            @if($article->featured_image)
                                <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" 
                                     class="img-fluid rounded mb-4" style="max-height: 400px; width: 100%; object-fit: cover;">
                            @endif

                            <div class="article-content">
                                {!! $article->content !!}
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    @if($article->tags && count($article->tags) > 0)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Tags</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <span class="badge bg-primary">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Article Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Artikel</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($article->status == 'published')
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Penulis:</strong></td>
                                    <td>{{ $article->author->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $article->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $article->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                                @if($article->published_at)
                                <tr>
                                    <td><strong>Dipublikasikan:</strong></td>
                                    <td>{{ $article->published_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Featured:</strong></td>
                                    <td>
                                        @if($article->is_featured)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star"></i> Ya
                                            </span>
                                        @else
                                            <span class="text-muted">Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Slug:</strong></td>
                                    <td><code>{{ $article->slug }}</code></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Excerpt -->
                    @if($article->excerpt)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-quote-left"></i> Ringkasan</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $article->excerpt }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-cog"></i> Aksi</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Artikel
                                </a>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Hapus Artikel
                                    </button>
                                </form>
                                @if($article->status == 'published')
                                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="btn btn-info">
                                        <i class="fas fa-external-link-alt"></i> Lihat di Website
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .article-content {
        line-height: 1.8;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    .article-content p {
        margin-bottom: 1rem;
    }
    .article-content h1, .article-content h2, .article-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
</style>
@endpush




