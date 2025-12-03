@extends('layouts.admin')

@section('title', 'Merek Kendaraan')
@section('page-title', 'Merek Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Daftar Merek</h4>
        <p class="text-muted">Kelola merek kendaraan rental</p>
    </div>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Merek
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
                        <th>Nama Merek</th>
                        <th>Jumlah Kendaraan</th>
                        <th>Status</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td><code>#{{ $brand->id }}</code></td>
                        <td>
                            <strong>{{ $brand->name }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill">
                                {{ $brand->vehicles_count }} kendaraan
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.brands.toggle-active', $brand) }}" method="POST" class="d-inline" id="toggle-form-{{ $brand->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="toggle-{{ $brand->id }}" 
                                           {{ $brand->is_active ? 'checked' : '' }}
                                           onchange="document.getElementById('toggle-form-{{ $brand->id }}').submit();">
                                    <label class="form-check-label" for="toggle-{{ $brand->id }}">
                                        <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $brand->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </label>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($brand->vehicles_count == 0)
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus merek ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-danger disabled" 
                                            title="Tidak bisa dihapus karena masih ada kendaraan">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada merek kendaraan</p>
                            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Merek Pertama
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

