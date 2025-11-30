@extends('layouts.app')

@section('title', 'Registrasi Member')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Prosedur Pendaftaran -->
        <div class="col-lg-5">
            <div class="card shadow h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Prosedur Pendaftaran Member</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Ikuti langkah-langkah berikut untuk menjadi member D'Sarana:</p>
                    
                    <div class="procedure-steps">
                        <div class="step-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>1</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Isi Form Registrasi</h6>
                                    <p class="text-muted small mb-0">Lengkapi data diri Anda dengan benar, termasuk nama lengkap, email, dan password yang aman.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>2</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Setujui Syarat & Ketentuan</h6>
                                    <p class="text-muted small mb-0">Baca dan setujui syarat dan ketentuan yang berlaku sebelum mendaftar.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>3</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Klik Tombol Daftar</h6>
                                    <p class="text-muted small mb-0">Setelah semua data terisi dengan benar, klik tombol "Daftar sebagai Member".</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>4</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Akses Dashboard Member</h6>
                                    <p class="text-muted small mb-0">Setelah registrasi berhasil, Anda akan langsung diarahkan ke dashboard member untuk melengkapi profil.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item mb-4">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>5</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Lengkapi Profil Anggota</h6>
                                    <p class="text-muted small mb-0">Isi data profil anggota Anda seperti alamat, nomor telepon, dan informasi lainnya yang diperlukan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="d-flex align-items-start">
                                <div class="step-number bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <strong>6</strong>
                                </div>
                                <div class="step-content">
                                    <h6 class="mb-2">Mulai Menggunakan Layanan</h6>
                                    <p class="text-muted small mb-0">Setelah profil lengkap, Anda dapat mulai menambahkan kendaraan, mengatur hari off, dan mengelola booking.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb"></i> Tips</h6>
                        <ul class="mb-0 small">
                            <li>Gunakan email yang aktif untuk menerima notifikasi</li>
                            <li>Gunakan password yang kuat dan mudah diingat</li>
                            <li>Pastikan data yang diisi sesuai dengan identitas Anda</li>
                            <li>Jika mengalami kendala, hubungi customer service kami</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Registrasi -->
        <div class="col-lg-7">
            <div class="card shadow">
                <div class="card-header bg-info text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Registrasi Member</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('member.register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 6 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="{{ route('register.terms') }}" target="_blank" class="text-primary">syarat dan ketentuan</a>
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-info text-white">
                                <i class="fas fa-user-plus"></i> Daftar sebagai Member
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">
                        Sudah punya akun member? 
                        <a href="{{ route('member.login') }}" class="text-primary">Login sekarang</a>
                    </p>
                    <p class="mb-0 mt-2">
                        <a href="{{ route('register') }}" class="text-muted">Daftar sebagai Customer</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .procedure-steps .step-item {
        position: relative;
    }
    
    .procedure-steps .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 50px;
        width: 2px;
        height: calc(100% - 20px);
        background: #dee2e6;
    }
    
    .step-number {
        font-size: 16px;
        font-weight: bold;
    }
    
    .step-content h6 {
        color: #333;
        font-weight: 600;
    }
</style>
@endpush
@endsection



