@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style>
    /* Vehicle Card Styling */
    .vehicle-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .vehicle-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .vehicle-card .card-img-top {
        transition: transform 0.3s ease;
    }
    
    .vehicle-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .vehicle-card .card-body {
        padding: 1.25rem;
    }
    
    .vehicle-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .vehicle-card .btn-primary {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    
    .vehicle-card .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 126, 52, 0.4);
    }
    
    /* Rental Category Name Styling */
    .rental-category-name {
        color: #28a745;
        font-weight: 600;
        font-size: 0.85em;
        margin-left: 0.5rem;
    }
    
    /* Dark Green Theme Override */
    .bg-primary, .btn-primary {
        background-color: #1e7e34 !important;
        border-color: #1e7e34 !important;
    }
    
    .bg-primary:hover, .btn-primary:hover {
        background-color: #1a6e2d !important;
        border-color: #1a6e2d !important;
    }
    
    .text-primary {
        color: #1e7e34 !important;
    }
</style>
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 text-white fw-bold">Rental Mobil & Bus Terpercaya Di Kota Batam</h1>
                <p class="lead text-white mb-4">Nikmati perjalanan nyaman dengan armada kendaraan berkualitas dan layanan terbaik. Tersedia berbagai pilihan mobil sedan, SUV, dan bus untuk kebutuhan Anda.</p>
                <a href="{{ url('/vehicles') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Lihat Kendaraan
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg ms-3">
                    <i class="fas fa-info-circle"></i> Pelajari Lebih
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Quick Booking Form -->
<!-- <section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-calendar-alt"></i> Cek Ketersediaan Kendaraan</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/vehicles') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="category" class="form-select">
                                        <option value="">Semua Kategori</option>
                                        @if(isset($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control" min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="end_date" class="form-control" min="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Cari Kendaraan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- Popular Vehicles -->
@if(isset($popularVehicles) && count($popularVehicles) > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Daftar Kendaraan</h2>
            <p class="lead">Pilih kendaraan yang sesuai dengan kebutuhan perjalanan Anda</p>
        </div>
        <div class="row g-4">
            @foreach($popularVehicles as $vehicle)
            <div class="col-lg-4 col-md-6">
                <div class="card vehicle-card h-100 shadow-sm">
                    <img src="{{ $vehicle->main_image }}" class="card-img-top" alt="{{ $vehicle->name }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicle->name }}</h5>
                        
                        <!-- Baris 1: Kategori dan Tipe Harga -->
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $vehicle->category->name }}</span>
                            @if(isset($vehicle->price_type) && $vehicle->price_type === 'with_driver')
                                <span class="badge bg-success ms-1">
                                    <i class="fas fa-user-tie"></i> {{ $vehicle->price_label ?? 'Dengan Sopir' }}
                                </span>
                            @elseif(isset($vehicle->price_type) && $vehicle->price_type === 'without_driver')
                                <span class="badge bg-info ms-1">
                                    <i class="fas fa-car"></i> {{ $vehicle->price_label ?? 'Tanpa Sopir' }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Baris 2: Brand dan Model -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-tag"></i> {{ $vehicle->brand_name ?? $vehicle->brand }}
                                @if($vehicle->model)
                                    - {{ $vehicle->model }}
                                @endif
                            </small>
                        </div>
                        
                        <!-- Baris 3: Harga -->
                        <div class="mb-3">
                            <h5 class="text-primary mb-0">
                                Rp. {{ number_format($vehicle->display_price ?? $vehicle->price_per_day ?? $vehicle->price_per_day_no_driver ?? 0) }}
                                /hari
                            </h5>
                        </div>
                        
                        <!-- Baris 4: Kursi, Transmisi, Bahan Bakar -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-users"></i>
                                </small>
                                <small class="text-muted">{{ $vehicle->seats }} Kursi</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-cog"></i>
                                </small>
                                <small class="text-muted">{{ $vehicle->transmission }}</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">
                                    <i class="fas fa-gas-pump"></i>
                                </small>
                                <small class="text-muted">{{ $vehicle->fuel_type }}</small>
                            </div>
                        </div>
                        
                        <!-- Baris 5: Button Detail dan Booking -->
                        @php
                            $whatsappPhone = config('services.whatsapp.admin_phone', '6282172292230');
                            // Format phone number (remove + if exists, ensure it starts with country code)
                            $whatsappPhone = preg_replace('/[^0-9]/', '', $whatsappPhone);
                            
                            // Build WhatsApp message with vehicle information
                            $message = "Halo, saya tertarik untuk booking kendaraan berikut:\n\n";
                            
                            $message .= "🏷️ Kategori: " . $vehicle->category->name . "\n";
                            
                            if (isset($vehicle->price_type)) {
                                $message .= "👤 Tipe: " . ($vehicle->price_type == 'with_driver' ? 'Dengan Sopir' : 'Tanpa Sopir') . "\n";
                            }
                           
                            
                            $message .= "👥 Kursi: " . $vehicle->seats . " penumpang\n";
                                                            
                            $message .= "\nMohon informasi lebih lanjut mengenai ketersediaan kendaraan, diskon & promo, dan proses booking Terima kasih!";
                            
                            // Encode message for URL
                            $encodedMessage = urlencode($message);
                            
                            // Create WhatsApp URL
                            $whatsappUrl = "https://wa.me/" . $whatsappPhone . "?text=" . $encodedMessage;
                        @endphp
                        <div class="d-flex gap-2">
                            <a href="{{ route('vehicles.show', $vehicle->slug) }}" class="btn btn-outline-primary flex-fill">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-primary flex-fill">
                                <i class="fab fa-whatsapp"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('vehicles.index') }}" class="btn btn-primary btn-lg px-5">
                <i class="fas fa-th-large"></i> Lihat Semua Kendaraan
            </a>
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Mengapa Memilih Kami?</h2>
            <p class="lead">Kami berkomitmen memberikan layanan rental terbaik untuk Anda</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-car fa-3x text-primary"></i>
                    </div>
                    <h4>Armada Berkualitas</h4>
                    <p class="text-muted">Kendaraan terawat dengan standar keamanan dan kenyamanan tinggi</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-clock fa-3x text-primary"></i>
                    </div>
                    <h4>Layanan 24/7</h4>
                    <p class="text-muted">Customer service siap melayani kapan saja untuk kebutuhan Anda</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-money-bill-wave fa-3x text-primary"></i>
                    </div>
                    <h4>Harga Terjangkau</h4>
                    <p class="text-muted">Tarif kompetitif dengan berbagai paket hemat sesuai kebutuhan</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Asuransi Lengkap</h4>
                    <p class="text-muted">Kendaraan dilindungi asuransi untuk keamanan perjalanan Anda</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Free Delivery</h4>
                    <p class="text-muted">Antar jemput kendaraan gratis untuk semua area di Batam</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h4>Driver Berpengalaman</h4>
                    <p class="text-muted">Dengan driver profesional dan berpengalaman</p>
                </div>
            </div>
        </div>
    </div>
</section>



<!-- Call to Action -->
<section class="py-5 text-white" style="background-color: #1e7e34;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold mb-4">Siap Untuk Perjalanan Anda?</h2>
                <p class="lead mb-4">Booking sekarang dan nikmati pengalaman rental terbaik bersama kami</p>
                <a href="{{ url('/vehicles') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-calendar-plus"></i> Booking Sekarang
                </a>
                <a href="{{ url('/contact') }}" class="btn btn-outline-light btn-lg ms-3">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection