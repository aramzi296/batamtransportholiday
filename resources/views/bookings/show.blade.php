@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Booking Saya</a></li>
            <li class="breadcrumb-item active">{{ $booking->booking_code }}</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Detail Booking</h2>
                    <p class="text-muted mb-0">Kode Booking: <strong>{{ $booking->booking_code }}</strong></p>
                </div>
                <div class="text-end">
                    @switch($booking->status)
                        @case('pending')
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="fas fa-clock me-1"></i>Menunggu Konfirmasi
                            </span>
                            @break
                        @case('confirmed')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check me-1"></i>Dikonfirmasi
                            </span>
                            @break
                        @case('completed')
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="fas fa-flag-checkered me-1"></i>Selesai
                            </span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="fas fa-times me-1"></i>Dibatalkan
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Status Information -->
            @if($booking->status === 'pending')
                <div class="alert alert-warning border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Booking Sedang Diproses</h6>
                            <p class="mb-0">Kami akan mengkonfirmasi booking Anda dalam waktu 24 jam. Harap tunggu dan pastikan nomor telepon Anda aktif.</p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'confirmed')
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Booking Dikonfirmasi</h6>
                            <p class="mb-0">Booking Anda telah dikonfirmasi! Silakan datang ke lokasi sesuai jadwal yang telah ditentukan.</p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'completed')
                <div class="alert alert-primary border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-star fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Rental Selesai</h6>
                            <p class="mb-0">Terima kasih telah menggunakan layanan kami! Kami harap Anda puas dengan pelayanan kami.</p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'cancelled')
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Booking Dibatalkan</h6>
                            <p class="mb-0">Booking ini telah dibatalkan. Jika ada pertanyaan, silakan hubungi customer service kami.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking Details -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Detail Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="booking-detail-item">
                                <label>Tanggal Booking:</label>
                                <span>{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Tanggal Mulai:</label>
                                <span>{{ $booking->start_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Tanggal Selesai:</label>
                                <span>{{ $booking->end_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="booking-detail-item">
                                <label>Durasi Rental:</label>
                                <span class="badge bg-info">{{ $booking->total_days }} hari</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Harga per Hari:</label>
                                <span>Rp {{ number_format($booking->daily_price) }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Total Harga:</label>
                                <span class="text-primary fw-bold fs-5">Rp {{ number_format($booking->total_price) }}</span>
                            </div>
                        </div>
                    </div>

                    @if($booking->notes)
                    <div class="mt-3 pt-3 border-top">
                        <label class="fw-semibold">Catatan Anda:</label>
                        <p class="mt-2 p-3 bg-light rounded">{{ $booking->notes }}</p>
                    </div>
                    @endif

                    @if($booking->admin_notes)
                    <div class="mt-3 pt-3 border-top">
                        <label class="fw-semibold">Catatan dari Admin:</label>
                        <p class="mt-2 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-4">{{ $booking->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-car me-2"></i>Informasi Kendaraan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $booking->vehicle->main_image }}" alt="{{ $booking->vehicle->name }}" 
                                 class="img-fluid rounded shadow-sm">
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-primary mb-2">{{ $booking->vehicle->name }}</h4>
                            <p class="text-muted mb-3">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="vehicle-spec">
                                        <i class="fas fa-id-card me-2 text-muted"></i>
                                        <span>{{ $booking->vehicle->plate_number }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="vehicle-spec">
                                        <i class="fas fa-palette me-2 text-muted"></i>
                                        <span>{{ $booking->vehicle->color }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="vehicle-spec">
                                        <i class="fas fa-users me-2 text-muted"></i>
                                        <span>{{ $booking->vehicle->seats }} kursi</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="vehicle-spec">
                                        <i class="fas fa-cog me-2 text-muted"></i>
                                        <span>{{ $booking->vehicle->transmission }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="vehicle-spec">
                                        <i class="fas fa-gas-pump me-2 text-muted"></i>
                                        <span>{{ $booking->vehicle->fuel_type }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Penyewa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="booking-detail-item">
                                <label>Nama Lengkap:</label>
                                <span>{{ $booking->customer_name }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Email:</label>
                                <span>{{ $booking->customer_email }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="booking-detail-item">
                                <label>No. Telepon:</label>
                                <span>{{ $booking->customer_phone }}</span>
                            </div>
                            <div class="booking-detail-item">
                                <label>Alamat:</label>
                                <span>{{ $booking->customer_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-phone me-2"></i>Butuh Bantuan?</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Tim customer service kami siap membantu Anda 24/7</p>
                    
                    <div class="d-grid gap-2">
                        <a href="tel:+62123456789" class="btn btn-outline-primary">
                            <i class="fas fa-phone me-2"></i>Telepon CS
                        </a>
                        <a href="mailto:cs@rentcarpro.com" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Email CS
                        </a>
                        <a href="https://wa.me/62123456789?text=Halo, saya ingin bertanya tentang booking {{ $booking->booking_code }}" 
                           class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp me-2"></i>Chat WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Penting</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Pastikan SIM Anda masih berlaku</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Datang tepat waktu sesuai jadwal</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bawa dokumen identitas asli</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Deposit akan dikembalikan jika tidak ada kerusakan</li>
                        <li class="mb-0"><i class="fas fa-check text-success me-2"></i>BBM ditanggung oleh penyewa</li>
                    </ul>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-list me-2"></i>Semua Booking Saya
                    </a>
                    <a href="{{ url('/vehicles') }}" class="btn btn-primary w-100">
                        <i class="fas fa-car me-2"></i>Rental Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .booking-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .booking-detail-item:last-child {
        border-bottom: none;
    }
    
    .booking-detail-item label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    .booking-detail-item span {
        color: #495057;
        font-weight: 500;
    }
    
    .vehicle-spec {
        display: flex;
        align-items: center;
        padding: 5px 0;
        font-size: 0.9rem;
    }
    
    .card {
        border: none;
        border-radius: 12px;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: 1px solid #e9ecef;
    }
    
    .alert {
        border-radius: 12px;
    }
    
    .badge {
        border-radius: 8px;
    }
    
    .btn {
        border-radius: 8px;
    }
</style>
@endpush