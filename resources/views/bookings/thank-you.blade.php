@extends('layouts.app')

@section('title', 'Terima Kasih - Booking ' . $booking->booking_code)

@section('content')
<div class="container py-5">
    <!-- Thank You Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Success Icon -->
            <div class="text-center mb-4">
                <div class="success-icon mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="display-5 fw-bold text-success">Terima Kasih!</h1>
                <p class="lead text-muted">Booking Anda telah berhasil dikirim</p>
            </div>

            <!-- Booking Code Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-body text-center py-4">
                    <h5 class="text-muted mb-2">Kode Booking Anda</h5>
                    <h2 class="text-primary fw-bold mb-0">{{ $booking->booking_code }}</h2>
                    <small class="text-muted">Simpan kode ini untuk referensi Anda</small>
                </div>
            </div>

            <!-- Email Notification -->
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-envelope-circle-check fa-2x me-3"></i>
                    <div>
                        <h6 class="mb-1">Email Konfirmasi Telah Dikirim</h6>
                        <p class="mb-0">Kami telah mengirimkan email konfirmasi booking ke <strong>{{ $booking->customer_email }}</strong>. Silakan cek inbox atau folder spam Anda. Email berisi detail lengkap booking Anda.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Booking Information -->
                <div class="col-lg-8">
                    <!-- Booking Details -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Booking</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Kendaraan</label>
                                        <h6 class="mb-0">{{ $booking->vehicle->name }}</h6>
                                        <small class="text-muted">{{ $booking->vehicle->brand_name }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Kategori</label>
                                        <h6 class="mb-0">{{ $booking->vehicle->category->name }}</h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Tanggal Mulai</label>
                                        <h6 class="mb-0">{{ $booking->start_date->format('d F Y') }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Tanggal Selesai</label>
                                        <h6 class="mb-0">{{ $booking->end_date->format('d F Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Durasi Rental</label>
                                        <h6 class="mb-0">{{ $booking->total_days }} hari</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="text-muted small">Status</label>
                                        <span class="badge bg-warning fs-6">Menunggu Konfirmasi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Informasi Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 mb-4">
                                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Pembayaran</h6>
                                <p class="mb-0">Pembayaran akan dilakukan setelah booking dikonfirmasi oleh admin. Tim kami akan menghubungi Anda dalam waktu 24 jam untuk proses pembayaran.</p>
                            </div>
                            
                            <div class="payment-details">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <span class="text-muted">Harga per Hari</span>
                                    <span class="fw-semibold">Rp {{ number_format($booking->daily_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <span class="text-muted">Durasi</span>
                                    <span class="fw-semibold">{{ $booking->total_days }} hari</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold fs-5">Total Estimasi</span>
                                    <span class="fw-bold text-primary fs-4">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-4 p-3 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Catatan:</strong> Harga di atas adalah estimasi. Harga final akan dikonfirmasi oleh admin setelah booking Anda dikonfirmasi.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Actions -->
                <div class="col-lg-4">
                    <!-- Contact Admin -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-headset me-2"></i>Hubungi Admin</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Jika ada pertanyaan atau butuh bantuan, hubungi kami:</p>
                            
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <strong>Telepon</strong>
                                </div>
                                <div class="ms-4">
                                    <a href="tel:{{ str_replace(' ', '', $adminContact['phone']) }}" class="text-decoration-none d-block mb-1">
                                        {{ $adminContact['phone'] }}
                                    </a>
                                    <a href="tel:{{ str_replace(' ', '', $adminContact['phone2']) }}" class="text-decoration-none d-block mb-1">
                                        {{ $adminContact['phone2'] }}
                                    </a>
                                    <a href="tel:{{ str_replace(' ', '', $adminContact['phone3']) }}" class="text-decoration-none d-block">
                                        {{ $adminContact['phone3'] }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="contact-item mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <strong>Email</strong>
                                </div>
                                <div class="ms-4">
                                    <a href="mailto:{{ $adminContact['email'] }}" class="text-decoration-none">
                                        {{ $adminContact['email'] }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <a href="https://wa.me/{{ str_replace(['+', ' '], '', $adminContact['phone']) }}?text=Halo, saya ingin bertanya tentang booking {{ $booking->booking_code }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i>Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-list-check me-2"></i>Langkah Selanjutnya</h6>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0 small">
                                <li class="mb-2">Tim kami akan menghubungi Anda dalam waktu 24 jam</li>
                                <li class="mb-2">Konfirmasi booking dan pembayaran</li>
                                <li class="mb-2">Siapkan dokumen (SIM, KTP, dll)</li>
                                <li class="mb-0">Datang sesuai jadwal yang telah ditentukan</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            @if($isOwner)
                            <div class="d-grid gap-2">
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail Booking
                                </a>
                                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>History Booking Saya
                                </a>
                                <a href="{{ url('/vehicles') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-car me-2"></i>Rental Lagi
                                </a>
                            </div>
                            @else
                            <div class="alert alert-info border-0 mb-3">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Tips:</strong> Daftar atau login untuk melihat dan mengatur semua booking Anda dengan mudah. Informasi booking juga telah dikirim ke email <strong>{{ $booking->customer_email }}</strong>.
                                </small>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login / Daftar
                                </a>
                                <a href="{{ url('/vehicles') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-car me-2"></i>Rental Lagi
                                </a>
                            </div>
                            @endif
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
    .success-icon {
        animation: scaleIn 0.5s ease-out;
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .info-item label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-item h6 {
        color: #2c3e50;
    }
    
    .payment-details {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
    }
    
    .contact-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .contact-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .card {
        border-radius: 12px;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
</style>
@endpush

