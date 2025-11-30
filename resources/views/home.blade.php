@extends('layouts.app')

@section('title', 'Home')

@section('content')
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
<section class="py-5 bg-light">
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
</section>

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

<!-- Popular Vehicles -->
@if(isset($popularVehicles) && count($popularVehicles) > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Kendaraan Populer</h2>
            <p class="lead">Pilihan favorit pelanggan kami</p>
        </div>
        <div class="row g-4">
            @foreach($popularVehicles as $vehicle)
            <div class="col-lg-4 col-md-6">
                <div class="card vehicle-card h-100">
                    <img src="{{ $vehicle->main_image }}" class="card-img-top" alt="{{ $vehicle->name }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicle->name }}</h5>
                        <p class="card-text text-muted">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">{{ $vehicle->category->name }}</span>
                            <h4 class="text-primary mb-0">Rp {{ number_format($vehicle->price_per_day) }}/hari</h4>
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-users"></i><br>
                                    {{ $vehicle->seats }} Kursi
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-cog"></i><br>
                                    {{ $vehicle->transmission }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="fas fa-gas-pump"></i><br>
                                    {{ $vehicle->fuel_type }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ url('/vehicles/' . $vehicle->slug) }}" class="btn btn-primary w-100">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ url('/vehicles') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large"></i> Lihat Semua Kendaraan
            </a>
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
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