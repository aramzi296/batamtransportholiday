@extends('layouts.app')

@section('title', $vehicle->name)

@push('styles')
<style>
    /* Elegant Booking Card Styling */
    .booking-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    
    /* Price Display */
    .price-section {
        position: relative;
        padding: 20px 0;
    }
    
    .price-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #1e7e34, #155724);
        border-radius: 2px;
    }
    
    .price-display {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .price-amount {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        line-height: 1;
    }
    
    .price-period {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 4px;
        font-weight: 500;
    }
    
    /* Form Inputs */
    .date-input {
        border-radius: 8px;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .date-input:focus {
        border-color: #1e7e34;
        box-shadow: 0 0 0 0.2rem rgba(30, 126, 52, 0.25);
    }
    
    .input-group-text {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
        padding: 12px;
    }
    
    /* Contact Form Styling - Blue Theme */
    #vehicleContactForm .form-control,
    #vehicleContactForm .form-select {
        border-radius: 8px;
        padding: 12px 16px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    #vehicleContactForm .form-control:focus,
    #vehicleContactForm .form-select:focus {
        border-color: #1e7e34;
        box-shadow: 0 0 0 0.2rem rgba(30, 126, 52, 0.25);
        outline: none;
    }
    
    #vehicleContactForm .input-group-text {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
        padding: 12px;
    }
    
    #vehicleContactForm .input-group-text i.text-primary {
        color: #1e7e34 !important;
    }
    
    #vehicleContactForm .input-group-text i.text-success {
        color: #28a745 !important;
    }
    
    #vehicleContactForm .form-label {
        color: #495057;
        font-weight: 600;
    }
    
    #vehicleContactForm .btn-primary {
        background: #1e7e34;
        border: none;
        color: white;
    }
    
    #vehicleContactForm .btn-primary:hover {
        background: #1a6e2d;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 126, 52, 0.4);
    }
    
    #vehicleContactForm .btn-primary:focus {
        box-shadow: 0 0 0 0.2rem rgba(30, 126, 52, 0.5);
    }
    
    /* Alert Styling */
    .alert-success {
        background: #28a745;
        border: none;
        color: white;
        border-radius: 12px;
    }
    
    .alert-danger {
        background: #dc3545;
        border: none;
        color: white;
        border-radius: 12px;
    }
    
    /* Calculation Card */
    .calculation-card {
        background: linear-gradient(135deg, #f1f8f4 0%, #e8f5e9 100%);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #c8e6c9;
    }
    
    .calculation-title {
        color: #1e7e34;
        margin-bottom: 16px;
        font-size: 0.95rem;
    }
    
    .calculation-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #495057;
    }
    
    .calculation-divider {
        border-color: #a5d6a7;
        margin: 12px 0;
    }
    
    .total-row {
        margin-bottom: 0;
        font-size: 1rem;
        padding-top: 8px;
    }
    
    .total-row .text-primary {
        font-size: 1.1rem;
    }
    
    /* Booking Button */
    .btn-booking {
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-transform: none;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
        position: relative;
        overflow: hidden;
    }
    
    .btn-booking:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 126, 52, 0.4);
    }
    
    .btn-booking:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-booking:hover:before {
        left: 100%;
    }
    
    /* Info Section */
    .info-section {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 12px;
        border-left: 4px solid #1e7e34;
    }
    
    /* Form Labels */
    .form-label {
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    /* Badge Styling */
    .badge.bg-light {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        border-radius: 6px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .price-amount {
            font-size: 1.8rem;
        }
        
        .btn-booking {
            padding: 12px 20px;
            font-size: 0.95rem;
        }
    }
    
    /* Featured Image Styling */
    .featured-image-container {
        position: relative;
        overflow: hidden;
    }
    
    .featured-image {
        transition: transform 0.3s ease;
    }
    
    .featured-image:hover {
        transform: scale(1.02);
    }
    
    /* Thumbnail Gallery Styling */
    .thumbnail-gallery {
        border-top: 1px solid #dee2e6;
    }
    
    .thumbnail-item {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .thumbnail-item:hover {
        border-color: #1e7e34;
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    
    /* Lightbox Modal Styling - Dark Background */
    /* Ensure dark backdrop when modal is open */
    .modal-backdrop.show {
        background-color: rgba(0, 0, 0, 0.95) !important;
        opacity: 1 !important;
    }
    
    /* Additional dark overlay for lightbox specifically */
    #imageLightbox.modal.show ~ .modal-backdrop,
    body.modal-open .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.95) !important;
        opacity: 1 !important;
    }
    
    #imageLightbox .modal-content {
        background: transparent;
        border: none;
    }
    
    /* Dark overlay for entire viewport when lightbox is open */
    body.modal-open {
        overflow: hidden;
    }
    
    #imageLightbox .modal-header {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1051;
        padding: 0;
    }
    
    #imageLightbox .btn-close {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        width: 45px;
        height: 45px;
        opacity: 1;
        padding: 0;
        transition: all 0.3s ease;
    }
    
    #imageLightbox .btn-close:hover {
        background-color: rgba(255, 255, 255, 1);
        transform: scale(1.1);
    }
    
    #imageLightbox .modal-body {
        padding: 20px;
        background: transparent;
    }
    
    #lightboxImage {
        border-radius: 8px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.8);
        transition: opacity 0.3s ease;
        background: rgba(255, 255, 255, 0.05);
        padding: 5px;
    }
    
    /* Lightbox Navigation Buttons */
    .lightbox-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1052;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex !important;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        opacity: 0.8;
    }
    
    .lightbox-nav-btn:hover {
        background-color: rgba(255, 255, 255, 1);
        opacity: 1;
        transform: translateY(-50%) scale(1.1);
    }
    
    .lightbox-prev {
        left: 20px;
    }
    
    .lightbox-next {
        right: 20px;
    }
    
    .lightbox-image-container {
        position: relative;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Image Counter */
    .lightbox-counter {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        z-index: 1052;
    }
    
    /* Hide navigation buttons on mobile if only one image */
    @media (max-width: 768px) {
        .lightbox-nav-btn {
            width: 40px;
            height: 40px;
        }
        
        .lightbox-prev {
            left: 10px;
        }
        
        .lightbox-next {
            right: 10px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/vehicles') }}">Kendaraan</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/vehicles?category=' . $vehicle->category->slug) }}">{{ $vehicle->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $vehicle->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Vehicle Images -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-0">
                    @php
                        $vehicleImages = $vehicle->vehicleImages;
                        $featuredImage = null;
                        $otherImages = collect();
                        $allImagesForLightbox = collect();
                        
                        if ($vehicleImages && $vehicleImages->count() > 0) {
                            $featuredImage = $vehicleImages->where('is_featured', true)->first();
                            $otherImages = $vehicleImages->where('is_featured', false);
                            
                            // Jika tidak ada featured image, gunakan gambar pertama sebagai featured
                            if (!$featuredImage && $vehicleImages->count() > 0) {
                                $featuredImage = $vehicleImages->first();
                                $otherImages = $vehicleImages->where('id', '!=', $featuredImage->id);
                            }
                            
                            // Prepare all images for lightbox slider (featured first, then others)
                            if ($featuredImage) {
                                $allImagesForLightbox->push($featuredImage);
                            }
                            $allImagesForLightbox = $allImagesForLightbox->merge($otherImages);
                        }
                    @endphp
                    
                    @if($vehicleImages && $vehicleImages->count() > 0)
                        <!-- Featured Image -->
                        <div class="featured-image-container">
                            <img src="{{ $featuredImage->image_url }}" 
                                 class="w-100 featured-image" 
                                 alt="{{ $vehicle->name }}" 
                                 style="height: 500px; object-fit: cover; cursor: pointer;"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#imageLightbox"
                                 data-image-index="0"
                                 onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        @if($otherImages->count() > 0)
                        <div class="thumbnail-gallery p-3 bg-light">
                            <div class="row g-2">
                                @foreach($otherImages as $index => $image)
                                <div class="col-3 col-md-2">
                                    <img src="{{ $image->thumbnail_url ?? $image->image_url }}" 
                                         class="img-thumbnail thumbnail-item" 
                                         alt="{{ $vehicle->name }}"
                                         style="width: 100%; height: 80px; object-fit: cover; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageLightbox"
                                         data-image-index="{{ $featuredImage ? $index + 1 : $index }}"
                                         onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @else
                        <img src="{{ $vehicle->main_image }}" class="w-100" alt="{{ $vehicle->name }}" 
                             style="height: 500px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                    @endif
                </div>
            </div>
            
            <!-- Lightbox Modal -->
            <div class="modal fade" id="imageLightbox" tabindex="-1" aria-labelledby="imageLightboxLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 text-center position-relative">
                            <!-- Previous Button -->
                            @if($allImagesForLightbox->count() > 1)
                            <button type="button" class="btn btn-light lightbox-nav-btn lightbox-prev" id="lightboxPrev" aria-label="Previous">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            @endif
                            
                            <!-- Image Container -->
                            <div class="lightbox-image-container">
                                <img id="lightboxImage" src="" alt="{{ $vehicle->name }}" class="img-fluid" style="max-height: 85vh; object-fit: contain;">
                            </div>
                            
                            <!-- Next Button -->
                            @if($allImagesForLightbox->count() > 1)
                            <button type="button" class="btn btn-light lightbox-nav-btn lightbox-next" id="lightboxNext" aria-label="Next">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            @endif
                            
                            <!-- Image Counter -->
                            @if($allImagesForLightbox->count() > 1)
                            <div class="lightbox-counter">
                                <span id="lightboxCurrent">1</span> / <span id="lightboxTotal">{{ $allImagesForLightbox->count() }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tentang Kendaraan -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Tentang Kendaraan</h4>
                </div>
                <div class="card-body">
                    <p>{{ $vehicle->description }}</p>
                </div>
            </div>

        </div>

        <!-- Booking Form -->
        <div class="col-lg-4">
            <div class="card booking-card shadow-lg">
                <div class="card-header bg-gradient-primary text-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fw-bold">{{ $vehicle->name }}</h5>
                            <small class="opacity-75">
                                <i class="fas fa-tag me-1"></i>{{ $vehicle->category->name }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-light text-primary px-2 py-1">
                                <i class="fas fa-star me-1"></i>Premium
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Price Section -->
                    <div class="text-center mb-4 price-section">
                        @if($vehicle->price_per_day && $vehicle->price_per_day_no_driver)
                            <!-- Both prices available -->
                            <div class="price-display mb-3">
                                <div class="mb-2">
                                    <span class="price-amount">Rp {{ number_format($vehicle->price_per_day) }}</span>
                                    <span class="price-period d-block">Dengan Sopir / hari</span>
                                </div>
                                <div class="mt-3 pt-3" style="border-top: 1px solid #dee2e6;">
                                    <span class="price-amount">Rp {{ number_format($vehicle->price_per_day_no_driver) }}</span>
                                    <span class="price-period d-block">Tanpa Sopir / hari</span>
                                </div>
                            </div>
                        @elseif($vehicle->price_per_day)
                            <!-- Only with driver price -->
                            <div class="price-display">
                                <span class="price-amount">Rp {{ number_format($vehicle->price_per_day) }}</span>
                                <span class="price-period">Dengan Sopir / hari</span>
                            </div>
                        @elseif($vehicle->price_per_day_no_driver)
                            <!-- Only without driver price -->
                            <div class="price-display">
                                <span class="price-amount">Rp {{ number_format($vehicle->price_per_day_no_driver) }}</span>
                                <span class="price-period">Tanpa Sopir / hari</span>
                            </div>
                        @endif
                        
                    </div>

                    <!-- Contact Form -->
                    <h6 class="mb-3 fw-semibold">
                        <i class="fas fa-envelope me-2 text-primary"></i>Kirim Pesan ke Admin
                    </h6>
                    <p class="text-muted small mb-3">Isi form di bawah ini untuk menanyakan tentang kendaraan ini.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('vehicles.contact', $vehicle->slug) }}" method="POST" id="vehicleContactForm">
                        @csrf
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        <input type="hidden" name="vehicle_name" value="{{ $vehicle->name }}">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small">Nama Lengkap *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-primary"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="alamat@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label fw-semibold small">Nomor WhatsApp *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fab fa-whatsapp text-success"></i>
                                </span>
                                <input type="tel" class="form-control border-start-0 @error('whatsapp') is-invalid @enderror" 
                                       id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                                       placeholder="08xxxxxxxxxx" required>
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label fw-semibold small">Pesan *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="4" 
                                      placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-booking">
                            <i class="fas fa-paper-plane me-2"></i>
                            <span>Kirim Pesan</span>
                        </button>
                    </form>

                    <!-- Info Section -->
                    <div class="info-section mt-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                            <small class="text-muted lh-sm">
                                Pesan Anda akan diteruskan ke admin. Tim kami akan menghubungi Anda dalam waktu <strong>24 jam</strong>.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mt-4">
                <div class="card-header text-center text-primary">
                    <h5 class="mb-0"><i class="fab fa-whatsapp"></i> Hubungi Petugas Kami</h5>
                </div>
                <div class="card-body">

                    <p class="mb-1 text-center"><i class="fab fa-whatsapp"></i> +62 821 7086 0825 (Mr. Febri)</p>
                    
                    <a href="https://wa.me/6282170860825?text=Halo, saya ingin bertanya tentang {{ $vehicle->name }}" 
                       class="btn btn-success w-100" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Vehicles -->
    @if($relatedVehicles->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Kendaraan Serupa</h3>
        <div class="row g-4">
            @foreach($relatedVehicles as $related)
            <div class="col-lg-4 col-md-6">
                <div class="card vehicle-card h-100">
                    <img src="{{ $related->main_image }}" class="card-img-top" alt="{{ $related->name }}" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $related->name }}</h5>
                        <p class="card-text text-muted">{{ $related->brand }} {{ $related->model }} ({{ $related->year }})</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">Rp {{ number_format($related->price_per_day) }}/hari</h5>
                            <a href="{{ url('/vehicles/' . $related->slug) }}" class="btn btn-outline-primary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Form submission with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const vehicleContactForm = document.getElementById('vehicleContactForm');
        if (vehicleContactForm) {
            vehicleContactForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
                submitBtn.disabled = true;
                
                // Re-enable button after a delay if form submission fails
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000); // 10 seconds timeout
            });
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    });
    
    
    // Lightbox functionality with slider
    document.addEventListener('DOMContentLoaded', function() {
        const lightboxModal = document.getElementById('imageLightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxPrev = document.getElementById('lightboxPrev');
        const lightboxNext = document.getElementById('lightboxNext');
        const lightboxCurrent = document.getElementById('lightboxCurrent');
        const lightboxTotal = document.getElementById('lightboxTotal');
        
        // Get all images data from PHP
        const allImages = @json($allImagesForLightbox->map(function($img) {
            return [
                'url' => $img->image_url,
                'id' => $img->id
            ];
        })->values()) || [];
        
        let currentImageIndex = 0;
        
        // If no images, return early
        if (allImages.length === 0) {
            return;
        }
        
        // Function to show image in lightbox
        function showImage(index) {
            // Handle circular navigation - loop back to start/end
            if (index < 0) {
                index = allImages.length - 1;
            } else if (index >= allImages.length) {
                index = 0;
            }
            
            currentImageIndex = index;
            lightboxImage.style.opacity = '0';
            
            setTimeout(function() {
                lightboxImage.src = allImages[index].url;
                if (lightboxCurrent) {
                    lightboxCurrent.textContent = index + 1;
                }
                lightboxImage.style.opacity = '1';
            }, 150);
            
            // Buttons are always enabled in circular mode
            if (lightboxPrev) {
                lightboxPrev.style.opacity = '0.8';
                lightboxPrev.style.pointerEvents = 'auto';
            }
            if (lightboxNext) {
                lightboxNext.style.opacity = '0.8';
                lightboxNext.style.pointerEvents = 'auto';
            }
        }
        
        // Handle clicks on featured image and thumbnails
        const imageElements = document.querySelectorAll('[data-bs-target="#imageLightbox"]');
        
        imageElements.forEach(function(element) {
            element.addEventListener('click', function() {
                const imageIndex = parseInt(this.getAttribute('data-image-index')) || 0;
                currentImageIndex = imageIndex;
                // Show image immediately when modal opens
                setTimeout(function() {
                    showImage(currentImageIndex);
                }, 100);
            });
        });
        
        // Initialize image when modal is shown
        lightboxModal.addEventListener('show.bs.modal', function() {
            showImage(currentImageIndex);
            // Ensure dark backdrop
            setTimeout(function() {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
                    backdrop.style.opacity = '1';
                }
            }, 10);
        });
        
        // Ensure dark backdrop when modal is shown
        lightboxModal.addEventListener('shown.bs.modal', function() {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
                backdrop.style.opacity = '1';
            }
        });
        
        // Navigation buttons (circular navigation)
        if (lightboxPrev) {
            lightboxPrev.addEventListener('click', function(e) {
                e.stopPropagation();
                showImage(currentImageIndex - 1);
            });
        }
        
        if (lightboxNext) {
            lightboxNext.addEventListener('click', function(e) {
                e.stopPropagation();
                showImage(currentImageIndex + 1);
            });
        }
        
        // Keyboard navigation (circular navigation)
        document.addEventListener('keydown', function(e) {
            if (!lightboxModal.classList.contains('show')) return;
            
            if (e.key === 'Escape') {
                const modal = bootstrap.Modal.getInstance(lightboxModal);
                if (modal) {
                    modal.hide();
                }
            } else if (e.key === 'ArrowLeft') {
                showImage(currentImageIndex - 1);
            } else if (e.key === 'ArrowRight') {
                showImage(currentImageIndex + 1);
            }
        });
        
        // Reset on modal close
        lightboxModal.addEventListener('hidden.bs.modal', function() {
            currentImageIndex = 0;
            if (lightboxImage) {
                lightboxImage.style.opacity = '1';
            }
        });
    });
</script>
@endpush