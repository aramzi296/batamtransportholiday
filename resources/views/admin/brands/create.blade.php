@extends('layouts.admin')

@section('title', 'Tambah Merek')
@section('page-title', 'Tambah Merek Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Merek</h5>
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

        <form action="{{ route('admin.brands.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Merek *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required placeholder="Contoh: Toyota, Honda, Suzuki">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Merek Aktif
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Merek
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



