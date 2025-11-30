@extends('layouts.admin')

@section('title', 'Kategori Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Kategori Artikel</h4>
        <p class="text-muted">Kelola kategori untuk artikel blog</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
        <i class="fas fa-plus"></i> Tambah Kategori
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Daftar Kategori Artikel
                </h5>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Artikel</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </td>
                                    <td>{{ Str::limit($category->description, 50) ?: '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->articles_count }} artikel</span>
                                    </td>
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
                                    <td>
                                        <small class="text-muted">
                                            {{ $category->created_at->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.article-categories.show', $category) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.article-categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus kategori artikel <strong>{{ $category->name }}</strong>?</p>
                                                        @if($category->articles_count > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Kategori ini memiliki {{ $category->articles_count }} artikel dan tidak dapat dihapus!
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        @if($category->articles_count == 0)
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-tags fa-4x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Belum ada kategori artikel</h5>
                        <p class="text-muted">Mulai dengan menambahkan kategori artikel pertama Anda.</p>
                        <a href="{{ route('admin.article-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori Artikel
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCategoryModalLabel">Tambah Kategori Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.article-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modalName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="modalName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="modalDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="modalIsActive" name="is_active" value="1" checked>
                        <label class="form-check-label" for="modalIsActive">
                            Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection