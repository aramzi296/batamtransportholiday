@extends('layouts.app')

@section('title', __('messages.about.title'))

@section('content')
<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">{{ __('messages.about.title') }}</h1>
                <p class="lead mb-4">{{ __('messages.about.subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <i class="fas fa-info-circle fa-5x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <!-- About Description -->
    <div class="row mb-5">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <p class="lead text-muted mb-4">
                        {{ __('messages.about.description') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-primary">
                        <i class="fas fa-eye me-2"></i>{{ __('messages.about.vision_title') }}
                    </h3>
                    <p class="mb-0">{{ __('messages.about.vision') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-primary">
                        <i class="fas fa-bullseye me-2"></i>{{ __('messages.about.mission_title') }}
                    </h3>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('messages.about.mission1') }}</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('messages.about.mission2') }}</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>{{ __('messages.about.mission3') }}</li>
                        <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>{{ __('messages.about.mission4') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-5">{{ __('messages.about.values_title') }}</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-star fa-3x text-warning"></i>
                            </div>
                            <h5 class="mb-3">{{ __('messages.about.value1_title') }}</h5>
                            <p class="text-muted mb-0">{{ __('messages.about.value1_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-shield-alt fa-3x text-primary"></i>
                            </div>
                            <h5 class="mb-3">{{ __('messages.about.value2_title') }}</h5>
                            <p class="text-muted mb-0">{{ __('messages.about.value2_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-heart fa-3x text-danger"></i>
                            </div>
                            <h5 class="mb-3">{{ __('messages.about.value3_title') }}</h5>
                            <p class="text-muted mb-0">{{ __('messages.about.value3_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm text-center h-100">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-lightbulb fa-3x text-info"></i>
                            </div>
                            <h5 class="mb-3">{{ __('messages.about.value4_title') }}</h5>
                            <p class="text-muted mb-0">{{ __('messages.about.value4_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-4 p-md-5 text-center">
                    <h3 class="mb-4">{{ __('messages.home.features_title') }}</h3>
                    <p class="lead mb-4">{{ __('messages.home.features_lead') }}</p>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <i class="fas fa-car fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.home.feature_quality') }}</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.home.feature_24_7') }}</p>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.home.feature_price') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-phone me-2"></i>{{ __('messages.home.contact_us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary {
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
    }
    
    .card {
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endsection
