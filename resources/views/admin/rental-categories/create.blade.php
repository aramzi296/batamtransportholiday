@extends('layouts.admin')

@section('title', 'Tambah Kategori Sewa')
@section('page-title', 'Tambah Kategori Sewa Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Kategori Sewa</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.rental-categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Kategori *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required placeholder="Contoh: Per Jam, Per Hari">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Deskripsi kategori sewa (opsional)">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Satuan *</label>
                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                    <option value="">Pilih Satuan</option>
                    <option value="jam" {{ old('satuan') == 'jam' ? 'selected' : '' }}>Jam</option>
                    <option value="hari" {{ old('satuan') == 'hari' ? 'selected' : '' }}>Hari</option>
                    <option value="setengah hari" {{ old('satuan') == 'setengah hari' ? 'selected' : '' }}>Setengah Hari</option>
                    <option value="bulan" {{ old('satuan') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                </select>
                <small class="form-text text-muted">Satuan untuk kategori sewa ini (jam, hari, setengah hari, atau bulan)</small>
                @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" 
                       value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                <small class="form-text text-muted">Urutan tampil (angka lebih kecil akan muncul lebih dulu)</small>
                @error('sort_order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Kategori Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.rental-categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

