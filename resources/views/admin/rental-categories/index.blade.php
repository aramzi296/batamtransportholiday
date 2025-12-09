@extends('layouts.admin')

@section('title', 'Kategori Sewa')
@section('page-title', 'Kategori Sewa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Daftar Kategori Sewa</h4>
        <p class="text-muted">Kelola kategori sewa kendaraan</p>
    </div>
    <a href="{{ route('admin.rental-categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th>Satuan</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><code>#{{ $category->id }}</code></td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td>
                            @if($category->satuan)
                                <span class="badge bg-info">{{ $category->satuan }}</span>
                            @else
                                <span class="badge bg-warning">Belum diisi</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.rental-categories.edit', $category) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.rental-categories.destroy', $category) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kategori sewa</p>
                            <a href="{{ route('admin.rental-categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Kategori Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

