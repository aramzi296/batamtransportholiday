@extends('layouts.app')

@section('title', 'Booking Kendaraan')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/vehicles') }}">Kendaraan</a></li>
            <li class="breadcrumb-item active">Booking</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Vehicle Selection -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-car"></i> Pilih Kendaraan</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm">
                        @csrf
                        
                        <!-- Vehicle Options -->
                        <div class="row g-4 mb-4">
                            @if($alternativeVehicle)
                            <!-- Alternative Vehicle (Default) -->
                            <div class="col-md-6">
                                <div class="card h-100 vehicle-option-card" style="border: 2px solid #28a745;">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="vehicle_id" 
                                                   id="vehicle_{{ $alternativeVehicle->id }}" 
                                                   value="{{ $alternativeVehicle->id }}" 
                                                   checked onchange="updateVehicleSelection({{ $alternativeVehicle->id }})">
                                            <label class="form-check-label fw-bold" for="vehicle_{{ $alternativeVehicle->id }}">
                                                <span class="badge bg-success">Rekomendasi</span>
                                            </label>
                                        </div>
                                        <img src="{{ $alternativeVehicle->main_image }}" class="card-img-top mb-3" 
                                             alt="{{ $alternativeVehicle->name }}" 
                                             style="height: 200px; object-fit: cover; border-radius: 8px;"
                                             onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                                        <h5 class="card-title">{{ $alternativeVehicle->name }}</h5>
                                        <p class="text-muted mb-3">{{ $alternativeVehicle->brand_name }} {{ $alternativeVehicle->model }} ({{ $alternativeVehicle->year }})</p>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-users"></i><br>
                                                    {{ $alternativeVehicle->seats }} Kursi
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-cog"></i><br>
                                                    {{ $alternativeVehicle->transmission }}
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-palette"></i><br>
                                                    {{ $alternativeVehicle->color }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <h4 class="text-primary mb-0">Rp {{ number_format($alternativeVehicle->price_per_day, 0, ',', '.') }}/hari</h4>
                                            <small class="text-muted">Nomor Antrian: {{ $alternativeVehicle->queue_number }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Selected Vehicle -->
                            <div class="col-md-{{ $alternativeVehicle ? '6' : '12' }}">
                                <div class="card h-100 vehicle-option-card" style="border: 2px solid #007bff;">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="vehicle_id" 
                                                   id="vehicle_{{ $selectedVehicle->id }}" 
                                                   value="{{ $selectedVehicle->id }}" 
                                                   {{ !$alternativeVehicle ? 'checked' : '' }} 
                                                   onchange="updateVehicleSelection({{ $selectedVehicle->id }})">
                                            <label class="form-check-label fw-bold" for="vehicle_{{ $selectedVehicle->id }}">
                                                <span class="badge bg-primary">Pilihan Anda</span>
                                            </label>
                                        </div>
                                        <img src="{{ $selectedVehicle->main_image }}" class="card-img-top mb-3" 
                                             alt="{{ $selectedVehicle->name }}" 
                                             style="height: 200px; object-fit: cover; border-radius: 8px;"
                                             onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                                        <h5 class="card-title">{{ $selectedVehicle->name }}</h5>
                                        <p class="text-muted mb-3">{{ $selectedVehicle->brand_name }} {{ $selectedVehicle->model }} ({{ $selectedVehicle->year }})</p>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-users"></i><br>
                                                    {{ $selectedVehicle->seats }} Kursi
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-cog"></i><br>
                                                    {{ $selectedVehicle->transmission }}
                                                </small>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted">
                                                    <i class="fas fa-palette"></i><br>
                                                    {{ $selectedVehicle->color }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <h4 class="text-primary mb-0">Rp {{ number_format($selectedVehicle->price_per_day, 0, ',', '.') }}/hari</h4>
                                            <small class="text-muted">Nomor Antrian: {{ $selectedVehicle->queue_number }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai *</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ $startDate ?? old('start_date') }}" required min="{{ date('Y-m-d') }}" 
                                       onchange="updatePrice()">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai *</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ $endDate ?? old('end_date') }}" required min="{{ date('Y-m-d') }}" 
                                       onchange="updatePrice()">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Driver Option (if both prices available) -->
                        @if($hasBothPrices)
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-tie me-2"></i>Pilih Layanan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded driver-option-card" id="driver_option_0" style="cursor: pointer; {{ !$withDriver ? 'border-primary bg-light' : '' }}" onclick="selectDriverOption(false)">
                                            <input class="form-check-input" type="radio" name="with_driver" id="without_driver" value="0" {{ !$withDriver ? 'checked' : '' }} onchange="updatePrice()">
                                            <label class="form-check-label w-100" for="without_driver" style="cursor: pointer;">
                                                <h6 class="mb-1">Tanpa Sopir</h6>
                                                <p class="text-muted mb-1 small">Anda menyetir sendiri</p>
                                                <strong class="text-primary">Rp {{ number_format($category->price, 0, ',', '.') }}/hari</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded driver-option-card" id="driver_option_1" style="cursor: pointer; {{ $withDriver ? 'border-primary bg-light' : '' }}" onclick="selectDriverOption(true)">
                                            <input class="form-check-input" type="radio" name="with_driver" id="with_driver" value="1" {{ $withDriver ? 'checked' : '' }} onchange="updatePrice()">
                                            <label class="form-check-label w-100" for="with_driver" style="cursor: pointer;">
                                                <h6 class="mb-1">Dengan Sopir</h6>
                                                <p class="text-muted mb-1 small">Sopir profesional tersedia</p>
                                                <strong class="text-primary">Rp {{ number_format($category->price_with_driver, 0, ',', '.') }}/hari</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Price Calculation -->
                        <div class="card bg-light mb-4" id="priceCalculation">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-calculator"></i> Rincian Biaya</h6>
                                @if($hasBothPrices)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Layanan</span>
                                    <span class="fw-semibold" id="serviceType">{{ $withDriver ? 'Dengan Sopir' : 'Tanpa Sopir' }}</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Durasi Rental</span>
                                    <span class="fw-semibold" id="totalDays">{{ $totalDays }} hari</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga per Hari</span>
                                    <span id="dailyPrice">Rp {{ number_format($selectedPrice ?? $defaultVehicle->price_per_day, 0, ',', '.') }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Biaya</span>
                                    <span class="fw-bold text-primary fs-5" id="totalPrice">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        @if (!empty($availabilityWarnings))
                            <div class="alert alert-warning mb-4">
                                <h6><i class="fas fa-exclamation-triangle"></i> Peringatan Ketersediaan</h6>
                                <p class="mb-2">Kendaraan tidak tersedia pada tanggal berikut:</p>
                                <ul class="mb-0">
                                    @foreach ($availabilityWarnings as $warning)
                                        <li><strong>{{ $warning['date'] }}</strong> - {{ $warning['reason'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr>

                        <!-- Customer Information -->
                        <h5 class="mb-3"><i class="fas fa-user"></i> Informasi Penyewa</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon *</label>
                                <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       value="{{ old('customer_phone') }}" required placeholder="08xxxxxxxxxx">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat *</label>
                                <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" 
                                          rows="3" required placeholder="Alamat lengkap">{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Keperluan khusus, permintaan tambahan, dll.">{{ old('notes') }}</textarea>
                        </div>

                        <hr>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Syarat dan Ketentuan</h5>
                            <div class="bg-light p-3 rounded">
                                <ul class="mb-0 small">
                                    <li>Booking akan dikonfirmasi dalam waktu 24 jam</li>
                                    <li>Pembayaran dilakukan setelah booking dikonfirmasi</li>
                                    <li>Penyewa harus memiliki SIM yang masih berlaku</li>
                                    <li>Deposit akan dikembalikan setelah kendaraan dikembalikan dalam kondisi baik</li>
                                    <li>Keterlambatan pengembalian dikenakan denda sesuai ketentuan</li>
                                    <li>Biaya BBM ditanggung oleh penyewa</li>
                                </ul>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="agree_terms" required>
                                <label class="form-check-label" for="agree_terms">
                                    Saya setuju dengan syarat dan ketentuan yang berlaku
                                </label>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane"></i> Kirim Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .vehicle-option-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .vehicle-option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .vehicle-option-card input[type="radio"]:checked ~ * {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    // Vehicle data for price calculation
    const vehicles = {
        @if($alternativeVehicle)
        {{ $alternativeVehicle->id }}: {
            price: {{ $alternativeVehicle->price_per_day }},
            name: "{{ addslashes($alternativeVehicle->name) }}"
        },
        @endif
        {{ $selectedVehicle->id }}: {
            price: {{ $selectedVehicle->price_per_day }},
            name: "{{ addslashes($selectedVehicle->name) }}"
        }
    };

    // Category price data
    @if(isset($category))
    const categoryPrices = {
        withoutDriver: {{ $category->price ?? 0 }},
        withDriver: {{ $category->price_with_driver ?? 0 }}
    };
    const hasPrice = {{ $hasPrice ? 'true' : 'false' }};
    const hasPriceWithDriver = {{ $hasPriceWithDriver ? 'true' : 'false' }};
    const hasBothPrices = {{ $hasBothPrices ? 'true' : 'false' }};
    @else
    const hasBothPrices = false;
    const hasPrice = false;
    const hasPriceWithDriver = false;
    @endif

    function selectDriverOption(withDriver) {
        document.getElementById(withDriver ? 'with_driver' : 'without_driver').checked = true;
        updatePrice();
    }

    function updateVehicleSelection(vehicleId) {
        // Update card borders
        document.querySelectorAll('.vehicle-option-card').forEach(card => {
            card.style.border = '2px solid #dee2e6';
        });
        const selectedCard = document.querySelector(`#vehicle_${vehicleId}`).closest('.vehicle-option-card');
        if (selectedCard) {
            selectedCard.style.border = '2px solid #28a745';
        }
        
        updatePrice();
    }

    function updatePrice() {
        const selectedVehicleId = document.querySelector('input[name="vehicle_id"]:checked').value;
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        
        let dailyPrice = vehicles[selectedVehicleId] ? vehicles[selectedVehicleId].price : 0;
        
        // Use category prices if available
        if (typeof categoryPrices !== 'undefined') {
            if (hasBothPrices) {
                // Both prices available, use selected driver option
                const withDriver = document.querySelector('input[name="with_driver"]:checked')?.value == '1';
                dailyPrice = withDriver ? categoryPrices.withDriver : categoryPrices.withoutDriver;
                
                // Update service type display
                const serviceTypeEl = document.getElementById('serviceType');
                if (serviceTypeEl) {
                    serviceTypeEl.textContent = withDriver ? 'Dengan Sopir' : 'Tanpa Sopir';
                }
                
            // Update driver option card styling
            const driverOption0 = document.getElementById('driver_option_0');
            const driverOption1 = document.getElementById('driver_option_1');
            if (driverOption0 && driverOption1) {
                if (withDriver) {
                    driverOption0.classList.remove('border-primary', 'bg-light');
                    driverOption1.classList.add('border-primary', 'bg-light');
                } else {
                    driverOption0.classList.add('border-primary', 'bg-light');
                    driverOption1.classList.remove('border-primary', 'bg-light');
                }
            }
            } else if (hasPriceWithDriver) {
                // Only price with driver available
                dailyPrice = categoryPrices.withDriver;
            } else if (hasPrice) {
                // Only price without driver available
                dailyPrice = categoryPrices.withoutDriver;
            }
        }
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
            
            const totalPrice = diffDays * dailyPrice;
            
            document.getElementById('totalDays').textContent = diffDays + ' hari';
            document.getElementById('dailyPrice').textContent = 'Rp ' + dailyPrice.toLocaleString('id-ID');
            document.getElementById('totalPrice').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        } else {
            document.getElementById('totalDays').textContent = '1 hari';
            document.getElementById('dailyPrice').textContent = 'Rp ' + dailyPrice.toLocaleString('id-ID');
            document.getElementById('totalPrice').textContent = 'Rp ' + dailyPrice.toLocaleString('id-ID');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const selectedVehicleId = document.querySelector('input[name="vehicle_id"]:checked').value;
        updateVehicleSelection(selectedVehicleId);
        updatePrice();
    });
</script>
@endpush
