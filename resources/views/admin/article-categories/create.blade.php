@extends('layouts.admin')

@section('title', 'Tambah Kategori Artikel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus text-primary me-2"></i>
        Tambah Kategori Artikel
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
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
                    Form Kategori Artikel
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.article-categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="Masukkan nama kategori artikel">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nama kategori akan digunakan sebagai slug URL secara otomatis.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  placeholder="Deskripsi kategori artikel (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-check text-success me-2"></i>
                                Kategori Aktif
                            </label>
                        </div>
                        <small class="text-muted">Kategori aktif akan ditampilkan dalam daftar pilihan saat membuat artikel.</small>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.article-categories.index') }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Panduan
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <h6>Tips Kategori Artikel:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Gunakan nama yang descriptif dan mudah dipahami
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Deskripsi membantu pembaca memahami konten kategori
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Kategori aktif akan muncul di menu navigasi website
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            URL slug dibuat otomatis dari nama kategori
                        </li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Contoh Kategori:</h6>
                    <ul class="list-unstyled">
                        <li><small class="text-muted">• Tips Berkendara</small></li>
                        <li><small class="text-muted">• Berita Otomotif</small></li>
                        <li><small class="text-muted">• Panduan Rental</small></li>
                        <li><small class="text-muted">• Event & Promo</small></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection