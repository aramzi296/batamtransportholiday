@extends('layouts.admin')

@section('title', 'Detail Kategori Artikel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye text-info me-2"></i>
        Detail Kategori Artikel
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="{{ route('admin.article-categories.edit', $category) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </div>
        <a href="{{ route('admin.article-categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tag me-2"></i>
                    {{ $category->name }}
                    @if($category->is_active)
                        <span class="badge bg-success ms-2">Aktif</span>
                    @else
                        <span class="badge bg-secondary ms-2">Nonaktif</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Dasar</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="30%"><strong>Nama:</strong></td>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Slug:</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah Artikel:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $category->articles->count() }} artikel</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Waktu</h6>
                        <table class="table table-sm">
                            <tr>
                                <td width="30%"><strong>Dibuat:</strong></td>
                                <td>
                                    {{ $category->created_at->format('d M Y H:i') }}
                                    <br><small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui:</strong></td>
                                <td>
                                    {{ $category->updated_at->format('d M Y H:i') }}
                                    <br><small class="text-muted">{{ $category->updated_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($category->description)
                <hr>
                <h6>Deskripsi</h6>
                <p class="text-muted">{{ $category->description }}</p>
                @endif
            </div>
        </div>
        
        @if($category->articles->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-newspaper me-2"></i>
                    Artikel dalam Kategori ({{ $category->articles->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->articles as $article)
                            <tr>
                                <td>
                                    <strong>{{ $article->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($article->excerpt, 80) }}</small>
                                </td>
                                <td>
                                    @if($article->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $article->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="border rounded p-3">
                            <h4 class="text-primary mb-0">{{ $category->articles->count() }}</h4>
                            <small class="text-muted">Total Artikel</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h5 class="text-success mb-0">{{ $category->articles->where('is_published', true)->count() }}</h5>
                            <small class="text-muted">Published</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <h5 class="text-warning mb-0">{{ $category->articles->where('is_published', false)->count() }}</h5>
                            <small class="text-muted">Draft</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h6 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.articles.create') }}?category={{ $category->id }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-2"></i>Tambah Artikel Baru
                    </a>
                    <a href="{{ route('admin.article-categories.edit', $category) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit Kategori
                    </a>
                    @if($category->is_active)
                        <small class="text-muted text-center">Kategori ini aktif dan terlihat di website</small>
                    @else
                        <small class="text-warning text-center">Kategori ini nonaktif dan tidak terlihat di website</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori artikel <strong>{{ $category->name }}</strong>?</p>
                @if($category->articles->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Kategori ini memiliki {{ $category->articles->count() }} artikel dan tidak dapat dihapus!
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                @if($category->articles->count() == 0)
                    <form action="{{ route('admin.article-categories.destroy', $category) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection