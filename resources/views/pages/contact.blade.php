@extends('layouts.app')

@section('title', __('messages.contact.title'))

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.contact.hero_title') }}</h1>
                <p class="lead mb-4">{{ __('messages.contact.hero_lead') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-phone fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-5">
        <!-- Map -->
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="https://wa.me/628136892535" class="btn btn-success btn-lg w-100" target="_blank" rel="noopener">
                    <i class="fab fa-whatsapp me-2"></i>{{ __('messages.contact.chat_now') }}
                </a>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="map-container">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.160570649037!2d104.0015280757133!3d1.0403928624908434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d98de3cdae0bed%3A0xaad20e034c1380ee!2sRumah%20Kopi%20Batam%20RKB!5e0!3m2!1sen!2sid!4v1764462441232!5m2!1sen!2sid"
                            width="100%"
                            height="450"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ __('messages.contact.address_line1') }}, {{ __('messages.contact.address_line2') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-4">
            <!-- Address -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">
                        <i class="fas fa-map-marker-alt me-3 text-primary"></i>{{ __('messages.contact.address') }}
                    </h4>
                    <p class="text-muted mb-0">
                        {{ __('messages.contact.address_line1') }}<br>
                        {{ __('messages.contact.address_line2') }}
                    </p>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="mb-4">
                        <i class="fas fa-clock me-3 text-primary"></i>{{ __('messages.contact.business_hours') }}
                    </h5>
                    
                    <div class="business-hours">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.contact.monday_friday') }}</span>
                            <span class="fw-semibold">08:00 - 20:00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.contact.saturday') }}</span>
                            <span class="fw-semibold">08:00 - 18:00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.contact.sunday') }}</span>
                            <span class="fw-semibold">09:00 - 17:00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ __('messages.contact.public_holidays') }}</span>
                            <span class="fw-semibold text-muted">{{ __('messages.contact.closed') }}</span>
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
    .bg-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    
    .contact-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 50%;
    }
    
    .contact-item {
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .contact-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .business-hours {
        font-size: 0.9rem;
    }
    
    .btn-social {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .btn-social:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .card {
        border-radius: 16px;
        overflow: hidden;
    }
    
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px 16px 0 0;
    }
    
</style>
@endpush