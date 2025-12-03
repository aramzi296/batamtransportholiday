@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user"></i> Profil Saya</h4>
                </div>
                <div class="card-body">
                    <!-- Informasi Akun -->
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Akun</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">
                            <strong>Nama:</strong> {{ $user->name }}
                        </li>
                        <li class="list-group-item">
                            <strong>Email:</strong> {{ $user->email }}
                        </li>
                        <li class="list-group-item">
                            <strong>Role:</strong> 
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'member' ? 'info' : 'secondary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </li>
                    </ul>

                    <hr>

                    <!-- Form Profil -->
                    <h5 class="mb-3"><i class="fas fa-edit"></i> Edit Profil</h5>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nomor_hp" class="form-label">Nomor HP</label>
                            <input type="text" class="form-control @error('nomor_hp') is-invalid @enderror" 
                                   id="nomor_hp" name="nomor_hp" 
                                   value="{{ old('nomor_hp', $dataAnggota['nomor_hp'] ?? '') }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('nomor_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nomor_whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" class="form-control @error('nomor_whatsapp') is-invalid @enderror" 
                                   id="nomor_whatsapp" name="nomor_whatsapp" 
                                   value="{{ old('nomor_whatsapp', $dataAnggota['nomor_whatsapp'] ?? '') }}" 
                                   placeholder="Contoh: 081234567890">
                            @error('nomor_whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="4" 
                                      placeholder="Masukkan alamat lengkap">{{ old('alamat', $dataAnggota['alamat'] ?? $dataAnggota['alamat_rumah'] ?? '') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Profil
                            </button>
                        </div>
                    </form>

                    <hr>

                    <!-- Menu Lainnya -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('password.change.form') }}" class="btn btn-info">
                            <i class="fas fa-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection