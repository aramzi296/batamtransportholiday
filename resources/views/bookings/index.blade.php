@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>Booking Saya</h2>
                    <p class="text-muted mb-0">Kelola dan pantau status booking Anda</p>
                </div>
                <a href="{{ url('/vehicles') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Booking Baru
                </a>
            </div>
        </div>
    </div>

    @if($bookings->count() > 0)
        <!-- Bookings List -->
        <div class="row g-4">
            @foreach($bookings as $booking)
            <div class="col-12">
                <div class="card booking-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Vehicle Image -->
                            <div class="col-md-2">
                                <img src="{{ $booking->vehicle->main_image }}" alt="{{ $booking->vehicle->name }}" 
                                     class="img-fluid rounded shadow-sm" style="height: 80px; width: 100%; object-fit: cover;">
                            </div>
                            
                            <!-- Booking Info -->
                            <div class="col-md-4">
                                <h5 class="mb-1">{{ $booking->vehicle->name }}</h5>
                                <p class="text-muted mb-1 small">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                                <span class="badge bg-secondary small">{{ $booking->booking_code }}</span>
                            </div>
                            
                            <!-- Dates & Duration -->
                            <div class="col-md-3">
                                <div class="booking-dates">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span class="small">{{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        <span class="small text-muted">{{ $booking->total_days }} hari</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status & Price -->
                            <div class="col-md-2">
                                <div class="text-center">
                                    @switch($booking->status)
                                        @case('pending')
                                            <span class="badge bg-warning mb-2">Pending</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-success mb-2">Dikonfirmasi</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary mb-2">Selesai</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger mb-2">Dibatalkan</span>
                                            @break
                                    @endswitch
                                    <div class="fw-bold text-primary">Rp {{ number_format($booking->total_price) }}</div>
                                </div>
                            </div>
                            
                            <!-- Action -->
                            <div class="col-md-1">
                                <div class="text-end">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Description -->
                        <div class="row mt-3">
                            <div class="col-12">
                                @switch($booking->status)
                                    @case('pending')
                                        <div class="alert alert-warning alert-sm mb-0 py-2">
                                            <i class="fas fa-clock me-2"></i>
                                            <small>Booking sedang diproses. Kami akan menghubungi Anda dalam 24 jam.</small>
                                        </div>
                                        @break
                                    @case('confirmed')
                                        <div class="alert alert-success alert-sm mb-0 py-2">
                                            <i class="fas fa-check me-2"></i>
                                            <small>Booking dikonfirmasi. Siap untuk rental sesuai jadwal.</small>
                                        </div>
                                        @break
                                    @case('completed')
                                        <div class="alert alert-primary alert-sm mb-0 py-2">
                                            <i class="fas fa-star me-2"></i>
                                            <small>Rental selesai. Terima kasih telah menggunakan layanan kami!</small>
                                        </div>
                                        @break
                                    @case('cancelled')
                                        <div class="alert alert-danger alert-sm mb-0 py-2">
                                            <i class="fas fa-times me-2"></i>
                                            <small>Booking dibatalkan. Hubungi CS untuk informasi lebih lanjut.</small>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $bookings->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                        <h4>Belum Ada Booking</h4>
                        <p class="text-muted mb-4">Anda belum memiliki booking kendaraan. Mulai rental kendaraan pertama Anda sekarang!</p>
                        <a href="{{ url('/vehicles') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-car me-2"></i>Mulai Rental
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Summary Cards -->
    @if($bookings->count() > 0)
    <div class="row mt-5">
        <div class="col-md-3">
            <div class="card bg-warning text-white text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4>{{ $bookings->where('status', 'pending')->count() }}</h4>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <i class="fas fa-check fa-2x mb-2"></i>
                    <h4>{{ $bookings->where('status', 'confirmed')->count() }}</h4>
                    <small>Dikonfirmasi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <i class="fas fa-flag-checkered fa-2x mb-2"></i>
                    <h4>{{ $bookings->where('status', 'completed')->count() }}</h4>
                    <small>Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center">
                <div class="card-body">
                    <i class="fas fa-times fa-2x mb-2"></i>
                    <h4>{{ $bookings->where('status', 'cancelled')->count() }}</h4>
                    <small>Dibatalkan</small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .booking-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .booking-dates {
        font-size: 0.9rem;
    }
    
    .alert-sm {
        font-size: 0.8rem;
        padding: 8px 12px;
    }
    
    .badge {
        border-radius: 6px;
        font-size: 0.75rem;
        padding: 6px 10px;
    }
    
    .btn {
        border-radius: 8px;
    }
    
    .card {
        border-radius: 12px;
    }
    
    /* Status specific styling */
    .bg-warning { background: linear-gradient(135deg, #ffc107, #ff8f00) !important; }
    .bg-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
    .bg-primary { background: linear-gradient(135deg, #007bff, #6f42c1) !important; }
    .bg-danger { background: linear-gradient(135deg, #dc3545, #e83e8c) !important; }
</style>
@endpush