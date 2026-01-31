@extends('layouts.app')

@section('title', __('messages.nav.home'))

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
        <div class="row justify-content-center text-center">
            <div class="col-lg-10">
                <h1 class="display-4 text-white fw-bold mb-4">{{ __('messages.home.cta_title') }}</h1>
                <ul class="list-unstyled text-white mb-4 fs-5">
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i>{{ __('messages.home.cta_point1') }}</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i>{{ __('messages.home.cta_point2') }}</li>
                    <li class="mb-2"><i class="fas fa-check-circle me-2 opacity-75"></i>{{ __('messages.home.cta_point3') }}</li>
                </ul>
                <p class="lead text-white mb-4">{{ __('messages.home.cta_lead') }}</p>
                <a href="{{ url('/vehicles') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-plus"></i> {{ __('messages.home.book_now') }}
                </a>
                <a href="{{ url('/contact') }}" class="btn btn-outline-light btn-lg ms-3">
                    <i class="fas fa-phone"></i> {{ __('messages.home.contact_us') }}
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

<!-- Galeri Foto Kendaraan -->
@php
    $galleryImages = [];
    if (isset($popularVehicles) && count($popularVehicles) > 0) {
        foreach ($popularVehicles as $vehicle) {
            $vehicleImages = $vehicle->vehicleImages ?? collect();
            if ($vehicleImages->count() > 0) {
                foreach ($vehicleImages as $img) {
                    $url = $img->image_url ?? null;
                    if ($url) {
                        $galleryImages[] = ['url' => $url, 'caption' => $vehicle->name];
                    }
                }
            } else {
                $mainUrl = $vehicle->main_image ?? null;
                if ($mainUrl) {
                    $galleryImages[] = ['url' => $mainUrl, 'caption' => $vehicle->name];
                }
            }
        }
    }
@endphp
@if(count($galleryImages) > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">{{ __('messages.home.gallery_title') }}</h2>
            <p class="lead">{{ __('messages.home.gallery_lead') }}</p>
        </div>
        <div class="row g-3" id="homeGalleryGrid">
            @foreach($galleryImages as $index => $item)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ $item['url'] }}" class="gallery-thumb d-block rounded overflow-hidden shadow-sm" data-index="{{ $index }}" data-bs-toggle="modal" data-bs-target="#homeGalleryLightbox">
                    <img src="{{ $item['url'] }}" class="w-100" alt="{{ $item['caption'] }}" style="height: 200px; object-fit: cover;" loading="lazy" onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                </a>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            @php
                $whatsappPhone = preg_replace('/[^0-9]/', '', config('services.whatsapp.admin_phone', '6282170860825'));
                $bookingMessage = urlencode("Halo, saya ingin booking kendaraan. Mohon informasi ketersediaan dan proses booking. Terima kasih!");
                $bookingWhatsAppUrl = "https://wa.me/" . $whatsappPhone . "?text=" . $bookingMessage;
            @endphp
            <a href="{{ $bookingWhatsAppUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg px-5">
                <i class="fab fa-whatsapp"></i> {{ __('messages.home.booking_now') }}
            </a>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="homeGalleryLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center position-relative">
                @if(count($galleryImages) > 1)
                <button type="button" class="btn btn-light home-lightbox-prev" id="homeLightboxPrev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                @endif
                <div class="home-lightbox-image-container">
                    <img id="homeLightboxImage" src="" alt="" class="img-fluid" style="max-height: 85vh; object-fit: contain;">
                    <div class="home-lightbox-caption text-white mt-2" id="homeLightboxCaption"></div>
                </div>
                @if(count($galleryImages) > 1)
                <button type="button" class="btn btn-light home-lightbox-next" id="homeLightboxNext" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                <div class="home-lightbox-counter text-white" id="homeLightboxCounter">1 / {{ count($galleryImages) }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .gallery-thumb:hover { opacity: 0.9; }
    #homeGalleryLightbox .modal-content { background: transparent !important; }
    #homeGalleryLightbox .modal-header { position: absolute; top: 0; right: 0; z-index: 10; }
    .home-lightbox-prev, .home-lightbox-next {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 5; border-radius: 50%; width: 48px; height: 48px; padding: 0;
    }
    .home-lightbox-prev { left: 1rem; }
    .home-lightbox-next { right: 1rem; }
    .home-lightbox-counter { position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%); z-index: 5; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var galleryData = @json($galleryImages);
    var lightboxModal = document.getElementById('homeGalleryLightbox');
    var lightboxImage = document.getElementById('homeLightboxImage');
    var lightboxCaption = document.getElementById('homeLightboxCaption');
    var lightboxCounter = document.getElementById('homeLightboxCounter');
    var currentIndex = 0;
    function showGalleryImage(i) {
        if (!galleryData.length) return;
        currentIndex = (i + galleryData.length) % galleryData.length;
        lightboxImage.src = galleryData[currentIndex].url;
        lightboxImage.alt = galleryData[currentIndex].caption;
        if (lightboxCaption) lightboxCaption.textContent = galleryData[currentIndex].caption;
        if (lightboxCounter) lightboxCounter.textContent = (currentIndex + 1) + ' / ' + galleryData.length;
    }
    if (lightboxModal) {
        lightboxModal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            currentIndex = (btn && btn.getAttribute('data-index') !== null) ? parseInt(btn.getAttribute('data-index'), 10) : 0;
            showGalleryImage(currentIndex);
        });
    }
    var prevBtn = document.getElementById('homeLightboxPrev');
    var nextBtn = document.getElementById('homeLightboxNext');
    if (prevBtn) prevBtn.onclick = function() { showGalleryImage(currentIndex - 1); };
    if (nextBtn) nextBtn.onclick = function() { showGalleryImage(currentIndex + 1); };
    document.querySelectorAll('#homeGalleryGrid [data-bs-toggle="modal"]').forEach(function(thumb) {
        thumb.addEventListener('click', function(ev) { ev.preventDefault(); });
    });
});
</script>
@endif

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">{{ __('messages.home.features_title') }}</h2>
            <p class="lead">{{ __('messages.home.features_lead') }}</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-car fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_quality') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_quality_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-clock fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_24_7') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_24_7_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-money-bill-wave fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_price') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_price_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_insurance') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_insurance_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_delivery') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_delivery_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="text-center h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h4>{{ __('messages.home.feature_driver') }}</h4>
                    <p class="text-muted">{{ __('messages.home.feature_driver_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection