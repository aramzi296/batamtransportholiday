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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: linear-gradient(90deg, #667eea, #764ba2);
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .input-group-text {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        background: #f8f9fa;
        padding: 12px;
    }
    
    /* Calculation Card */
    .calculation-card {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e6e9ff;
    }
    
    .calculation-title {
        color: #4c63d2;
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
        border-color: #d1d9ff;
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .btn-booking:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
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
        border-left: 4px solid #667eea;
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
                    @endphp
                    @if($vehicleImages && $vehicleImages->count() > 0)
                        <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($vehicleImages as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ $image->image_url }}" class="d-block w-100" alt="{{ $vehicle->name }}" 
                                         style="height: 400px; object-fit: cover;"
                                         onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                                </div>
                                @endforeach
                            </div>
                            @if($vehicleImages->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            @endif
                        </div>
                    @else
                        <img src="{{ $vehicle->main_image }}" class="w-100" alt="{{ $vehicle->name }}" 
                             style="height: 400px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                    @endif
                </div>
            </div>

            <!-- Vehicle Details -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-info-circle"></i> Detail Kendaraan</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Brand:</strong> {{ $vehicle->brand }}
                        </div>
                        <div class="col-md-6">
                            <strong>Model:</strong> {{ $vehicle->model }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tahun:</strong> {{ $vehicle->year }}
                        </div>
                        <div class="col-md-6">
                            <strong>Warna:</strong> {{ $vehicle->color }}
                        </div>
                        <div class="col-md-6">
                            <strong>Jumlah Kursi:</strong> {{ $vehicle->seats }} kursi
                        </div>
                        <div class="col-md-6">
                            <strong>Transmisi:</strong> {{ $vehicle->transmission }}
                        </div>
                        <div class="col-md-6">
                            <strong>Bahan Bakar:</strong> {{ $vehicle->fuel_type }}
                        </div>
                        <div class="col-md-6">
                            <strong>Plat Nomor:</strong> {{ $vehicle->plate_number }}
                        </div>
                    </div>

                    @if($vehicle->features && count($vehicle->features) > 0)
                    <div class="mt-4">
                        <strong>Fitur & Fasilitas:</strong>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($vehicle->features as $feature)
                                <span class="badge bg-primary">{{ $feature }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <strong>Deskripsi:</strong>
                        <p class="mt-2">{{ $vehicle->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicle Calendar -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Kalender Ketersediaan</h5>
                </div>
                <div class="card-body">
                    @php
                        $calendarItems = \App\Models\VehicleCalendar::where('vehicle_id', $vehicle->id)->orderBy('date')->get();
                        $today = \Carbon\Carbon::today();
                        $next30 = \Carbon\Carbon::today()->addDays(29);
                        $dates = [];
                        for ($date = $today->copy(); $date <= $next30; $date->addDay()) {
                            $dates[] = $date->copy();
                        }
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dates as $date)
                                    @php
                                        $item = $calendarItems->firstWhere('date', $date->format('Y-m-d'));
                                    @endphp
                                    <tr>
                                        <td>{{ $date->format('d M Y') }}</td>
                                        <td>
                                            @if ($item)
                                                @if ($item->blocked_by == 'booking')
                                                    <span class="badge bg-warning text-dark">Dibooking</span>
                                                @else
                                                    <span class="badge bg-danger">Blok Admin</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success">Tersedia</span>
                                            @endif
                                        </td>
                                        <td>{{ $item ? ($item->reason ?? '-') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <small class="text-muted">* Kalender menampilkan 30 hari ke depan.</small>
                    </div>
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
                        <div class="price-display">
                            <span class="price-amount">Rp {{ number_format($vehicle->price_per_day) }}</span>
                            <span class="price-period">per hari</span>
                        </div>
                        <small class="text-success d-block mt-1">
                            <i class="fas fa-check-circle me-1"></i>Harga sudah termasuk asuransi
                        </small>
                    </div>

                    @if($vehicle->is_available)
                        <form action="{{ url('/booking') }}" method="GET" id="bookingForm">
                            <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                            
                            <!-- Date Inputs -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-semibold">Tanggal Mulai</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 bg-light">
                                            <i class="fas fa-calendar-alt text-primary"></i>
                                        </span>
                                        <input type="date" name="start_date" class="form-control border-start-0 date-input" 
                                               required min="{{ date('Y-m-d') }}" id="startDate">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-semibold">Tanggal Selesai</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 bg-light">
                                            <i class="fas fa-calendar-check text-primary"></i>
                                        </span>
                                        <input type="date" name="end_date" class="form-control border-start-0 date-input" 
                                               required min="{{ date('Y-m-d') }}" id="endDate">
                                    </div>
                                </div>
                            </div>

                            <!-- Calculation Result -->
                            <div class="mb-4" id="calculationResult" style="display: none;">
                                <div class="calculation-card">
                                    <h6 class="calculation-title">
                                        <i class="fas fa-calculator me-2"></i>Rincian Biaya
                                    </h6>
                                    <div class="calculation-item">
                                        <span>Durasi rental</span>
                                        <span class="fw-semibold" id="totalDays">0 hari</span>
                                    </div>
                                    <div class="calculation-item">
                                        <span>Harga per hari</span>
                                        <span>Rp {{ number_format($vehicle->price_per_day) }}</span>
                                    </div>
                                    <hr class="calculation-divider">
                                    <div class="calculation-item total-row">
                                        <span class="fw-bold">Total Estimasi</span>
                                        <span class="fw-bold text-primary" id="totalPrice">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 btn-booking">
                                <i class="fas fa-calendar-plus me-2"></i>
                                <span>Booking Sekarang</span>
                            </button>
                        </form>

                        <!-- Info Section -->
                        <div class="info-section mt-3">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <small class="text-muted lh-sm">
                                    Booking akan dikonfirmasi dalam <strong>24 jam</strong>. 
                                    Pembayaran dapat dilakukan setelah konfirmasi.
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="alert alert-warning border-0 mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Kendaraan sedang tidak tersedia
                            </div>
                            <a href="{{ url('/vehicles') }}" class="btn btn-outline-primary w-100 btn-booking">
                                <i class="fas fa-search me-2"></i>Cari Kendaraan Lain
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-phone"></i> Butuh Bantuan?</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Customer Service</strong></p>
                    <p class="mb-1"><i class="fas fa-phone"></i> +62 821 7086 0825</p>
                    <p class="mb-1"><i class="fas fa-phone"></i> +62 813 6481 0770</p>
                    <p class="mb-1"><i class="fas fa-phone"></i> +62 811 700 7201</p>
                    <p class="mb-1"><i class="fas fa-envelope"></i> info@dsarana.com</p>
                    <p class="mb-1"><i class="fab fa-whatsapp"></i> WhatsApp: +62 821 7086 0825</p>
                    <p class="mb-1"><i class="fab fa-whatsapp"></i> WhatsApp: +62 813 6481 0770</p>
                    <p class="mb-1"><i class="fab fa-whatsapp"></i> WhatsApp: +62 811 700 7201</p>
                    
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
    const pricePerDay = {{ $vehicle->price_per_day }};
    
    function calculateTotal() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 0) {
                const totalPrice = diffDays * pricePerDay;
                
                document.getElementById('totalDays').textContent = diffDays + ' hari';
                document.getElementById('totalPrice').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
                document.getElementById('calculationResult').style.display = 'block';
            } else {
                document.getElementById('calculationResult').style.display = 'none';
            }
        }
    }
    
    document.getElementById('startDate').addEventListener('change', function() {
        const endDateInput = document.getElementById('endDate');
        const startDate = new Date(this.value);
        
        // Set minimum end date to be after start date
        const nextDay = new Date(startDate);
        nextDay.setDate(nextDay.getDate() + 1);
        endDateInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
        
        // If end date is before new start date, update it
        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = nextDay.toISOString().split('T')[0];
        }
        
        calculateTotal();
    });
    
    document.getElementById('endDate').addEventListener('change', calculateTotal);
</script>
@endpush