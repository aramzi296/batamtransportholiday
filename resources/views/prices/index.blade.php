@extends('layouts.app')

@section('title', 'Daftar Harga Rental Kendaraan')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary mb-3">
            <i class="fas fa-tags"></i> Daftar Harga Rental
        </h1>
        <p class="lead text-muted">
            Pilih paket rental sesuai kebutuhan Anda. Harga dapat bervariasi tergantung kategori kendaraan dan layanan yang dipilih.
        </p>
    </div>

    @if($categories->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Belum ada daftar harga tersedia saat ini.
        </div>
    @else
        <!-- Price Cards -->
        <div class="row g-4 mb-5">
            @foreach($categories as $category)
                <div class="col-lg-6 col-xl-4">
                    <div class="card h-100 shadow-sm price-card border-0">
                        <div class="card-header bg-primary text-white text-center py-4">
                            <h3 class="mb-0 fw-bold">
                                <i class="fas fa-car"></i> {{ $category->name }}
                            </h3>
                            @if($category->description)
                                <p class="mb-0 mt-2 small opacity-90">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                        </div>
                        <div class="card-body p-4">
                            <!-- Price Without Driver -->
                            @if($category->price && $category->price > 0)
                                <div class="price-item mb-4 pb-4 border-bottom">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user text-muted me-2"></i>
                                        <span class="text-muted small">Tanpa Sopir</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0 text-primary fw-bold">
                                                Rp {{ number_format($category->price, 0, ',', '.') }}
                                            </h4>
                                            <small class="text-muted">per hari</small>
                                        </div>
                                        <div class="badge bg-light text-dark">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Price With Driver -->
                            @if($category->price_with_driver && $category->price_with_driver > 0)
                                <div class="price-item">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user-tie text-primary me-2"></i>
                                        <span class="text-muted small">Dengan Sopir</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0 text-primary fw-bold">
                                                Rp {{ number_format($category->price_with_driver, 0, ',', '.') }}
                                            </h4>
                                            <small class="text-muted">per hari</small>
                                        </div>
                                        <div class="badge bg-primary text-white">
                                            <i class="fas fa-star"></i> Recommended
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- No Price Available -->
                            @if((!$category->price || $category->price == 0) && (!$category->price_with_driver || $category->price_with_driver == 0))
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-info-circle"></i> Harga belum tersedia
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light border-0 text-center py-3">
                            <a href="{{ route('vehicles.index', ['category' => $category->slug]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Lihat Kendaraan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Additional Info Section -->
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle text-primary"></i> Informasi Penting
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                    <div>
                                        <strong>Tanpa Sopir</strong>
                                        <p class="mb-0 small text-muted">Anda menyetir sendiri. Cocok untuk yang sudah berpengalaman.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-primary me-2 mt-1"></i>
                                    <div>
                                        <strong>Dengan Sopir</strong>
                                        <p class="mb-0 small text-muted">Sopir profesional tersedia. Lebih nyaman dan aman untuk perjalanan.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-calendar text-info me-2 mt-1"></i>
                                    <div>
                                        <strong>Durasi Rental</strong>
                                        <p class="mb-0 small text-muted">Harga dihitung per hari. Minimal 1 hari rental.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-phone text-success me-2 mt-1"></i>
                                    <div>
                                        <strong>Konsultasi</strong>
                                        <p class="mb-0 small text-muted">Hubungi kami untuk informasi lebih lanjut atau penawaran khusus.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('contact') }}" class="btn btn-outline-primary">
                                <i class="fas fa-envelope"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .price-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2), 0 2px 8px rgba(0,0,0,0.15) !important;
    }
    
    .price-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3), 0 8px 20px rgba(0,0,0,0.2) !important;
    }
    
    .price-card .card-header {
        border-radius: 0;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .price-item {
        transition: all 0.3s ease;
    }
    
    .price-item:hover {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 0.5rem;
        margin: -0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    
    .card.bg-light {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2), 0 2px 8px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
@endsection

