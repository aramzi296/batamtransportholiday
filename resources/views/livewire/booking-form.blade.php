@php
    // Initialize unitDisplay with default value at the very beginning
    $unitDisplay = 'hari';
@endphp
<div>
    @if(!$vehicle)
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> Kendaraan tidak ditemukan.
        </div>
    @else
    @php
        // Calculate unitDisplay based on rental category
        try {
            if (isset($vehicle) && $vehicle && isset($rental_category_id) && $rental_category_id) {
                $rentalCategories = $vehicle->rentalCategories->where('price', '>', 0)->groupBy('rental_category_id');
                if ($rentalCategories->has($rental_category_id)) {
                    $selectedGroup = $rentalCategories->get($rental_category_id);
                    $withDriverValue = isset($with_driver) ? $with_driver : false;
                    $selectedVehicleRentalCategory = $selectedGroup->where('with_driver', $withDriverValue)->first();
                    if ($selectedVehicleRentalCategory && $selectedVehicleRentalCategory->rentalCategory) {
                        $unitDisplay = $selectedVehicleRentalCategory->rentalCategory->satuan ?? 'hari';
                    }
                }
            }
        } catch (\Exception $e) {
            // If any error, use default 'hari'
            $unitDisplay = 'hari';
        }
    @endphp
    <!-- Progress Steps -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="step-item {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                            <div class="step-number">
                                @if($currentStep > 1)
                                    <i class="fas fa-check"></i>
                                @else
                                    1
                                @endif
                            </div>
                            <div class="step-label">Pilih Kendaraan</div>
                        </div>
                        <div class="step-connector {{ $currentStep >= 2 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
                            <div class="step-number">
                            @if($currentStep > 2)
                                <i class="fas fa-check"></i>
                            @else
                                2
                            @endif
                        </div>
                            <div class="step-label">Pilih Tanggal</div>
                        </div>
                        <div class="step-connector {{ $currentStep >= 3 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
                            <div class="step-number">
                            @if($currentStep > 3)
                                <i class="fas fa-check"></i>
                            @else
                                3
                            @endif
                        </div>
                            <div class="step-label">Data Penyewa</div>
                        </div>
                        <div class="step-connector {{ $currentStep >= 4 ? 'active' : '' }}"></div>
                        <div class="step-item {{ $currentStep >= 4 ? 'active' : '' }}">
                            <div class="step-number">4</div>
                            <div class="step-label">Detil Booking</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .step-number i {
            font-size: 14px;
        }
        .step-item.active .step-number {
            background: #667eea;
            color: white;
        }
        .step-item.completed .step-number {
            background: #28a745;
            color: white;
        }
        .step-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .step-item.active .step-label {
            color: #667eea;
            font-weight: 600;
        }
        .step-connector {
            flex: 1;
            height: 2px;
            background: #e9ecef;
            margin: 0 10px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .step-connector.active {
            background: #667eea;
        }
    </style>

    <!-- Step 1: Pilih Kendaraan -->
    @if($currentStep == 1)
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-car"></i> Pilih Kendaraan</h5>
        </div>
        <div class="card-body">
            @php
                // Get all vehicles to display (alternatives + selected if applicable)
                $vehiclesToDisplay = [];
                
                // Always include selected vehicle
                if ($vehicle) {
                    $selectedVehicleData = [
                        'id' => $vehicle->id,
                        'name' => $vehicle->name ?? '',
                        'brand' => is_string($vehicle->brand) ? $vehicle->brand : ($vehicle->brand->name ?? $vehicle->brand_name ?? ''),
                        'model' => $vehicle->model ?? '',
                        'seats' => $vehicle->seats ?? null,
                        'price_per_day' => $vehicle->price_per_day ?? 0,
                        'price_per_day_no_driver' => $vehicle->price_per_day_no_driver ?? null,
                        'queue_number' => $vehicle->queue_number ?? null,
                    ];
                    
                    // If there are alternatives, add them
                    if ($showVehicleSelection && count($alternativeVehicles) > 0) {
                        // Check if selected vehicle is already in alternatives
                        $selectedInAlternatives = false;
                        foreach ($alternativeVehicles as $altVehicle) {
                            if ($altVehicle['id'] == $vehicle->id) {
                                $selectedInAlternatives = true;
                            }
                            $vehiclesToDisplay[] = $altVehicle;
                        }
                        
                        // If selected vehicle not in alternatives, add it
                        if (!$selectedInAlternatives) {
                            $vehiclesToDisplay[] = $selectedVehicleData;
                        }
                    } else {
                        // Only selected vehicle
                        $vehiclesToDisplay[] = $selectedVehicleData;
                    }
                }
            @endphp
            
            @if(count($vehiclesToDisplay) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">Pilih</th>
                            <th style="width: 120px;">Thumbnail</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Seats</th>
                            <th class="text-end">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehiclesToDisplay as $veh)
                        @php
                            $vehModel = \App\Models\Vehicle::find($veh['id']);
                            $vehImage = $vehModel ? $vehModel->vehicleImages->where('is_featured', true)->first() : null;
                            if (!$vehImage && $vehModel) {
                                $vehImage = $vehModel->vehicleImages->first();
                            }
                            $vehPrice = $with_driver ? ($veh['price_per_day'] ?? 0) : ($veh['price_per_day_no_driver'] ?? $veh['price_per_day'] ?? 0);
                            $isSelected = $selectedVehicleForBooking == $veh['id'];
                        @endphp
                        <tr class="{{ $isSelected ? 'table-primary' : '' }}" 
                            style="cursor: pointer;"
                            onclick="$wire.set('selectedVehicleForBooking', {{ $veh['id'] }})">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           wire:model="selectedVehicleForBooking" 
                                           value="{{ $veh['id'] }}" 
                                           id="vehicle_{{ $veh['id'] }}">
                                </div>
                            </td>
                            <td>
                                @if($vehImage)
                                    <img src="{{ $vehImage->image_url }}" 
                                         alt="{{ $veh['name'] }}" 
                                         class="img-thumbnail"
                                         style="width: 100px; height: 75px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center img-thumbnail"
                                         style="width: 100px; height: 75px;">
                                        <i class="fas fa-car text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $veh['brand'] ?? '' }}</strong></td>
                            <td>{{ $veh['model'] ?? '' }}</td>
                            <td>{{ $veh['seats'] ?? '-' }} kursi</td>
                            <td class="text-end">
                                <strong class="text-primary">Rp {{ number_format($vehPrice) }}</strong>
                                <small class="text-muted d-block">/hari</small>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> Tidak ada kendaraan yang tersedia.
            </div>
            @endif
            
            @error('selectedVehicleForBooking')
                <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary" wire:click="nextStep">
            Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
        </button>
    </div>
    
    @endif

    <!-- Step 2: Pilih Tanggal -->
    @if($currentStep == 2)
    <div class="row">
        <!-- Vehicle Info & Features -->
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-car"></i> {{ $vehicle->name }}</h5>
                </div>
                <div class="card-body p-0">
                    @php
                        $featuredImage = $vehicle->vehicleImages->where('is_featured', true)->first();
                        $firstImage = $featuredImage ?? $vehicle->vehicleImages->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ $firstImage->image_url }}" class="w-100" alt="{{ $vehicle->name }}" 
                             style="height: 300px; object-fit: cover;">
                    @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-car fa-5x text-white"></i>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-info-circle"></i> Informasi Kendaraan</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td><strong>Kategori:</strong></td>
                                <td><span class="badge bg-primary">{{ $vehicle->category->name }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Merek:</strong></td>
                                <td>{{ $vehicle->brand_name ?? $vehicle->brand }}</td>
                            </tr>
                            <tr>
                                <td><strong>Model:</strong></td>
                                <td>{{ $vehicle->model }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tahun:</strong></td>
                                <td>{{ $vehicle->year }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kursi:</strong></td>
                                <td>{{ $vehicle->seats }} kursi</td>
                            </tr>
                            <tr>
                                <td><strong>Transmisi:</strong></td>
                                <td>{{ $vehicle->transmission }}</td>
                            </tr>
                            <tr>
                                <td><strong>Bahan Bakar:</strong></td>
                                <td>{{ $vehicle->fuel_type }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    @if($vehicle->features && count($vehicle->features) > 0)
                    <div>
                        <h6><i class="fas fa-star"></i> Fitur & Fasilitas</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($vehicle->features as $feature)
                                <span class="badge bg-secondary">{{ $feature }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Date Input & Price -->
        <div class="col-lg-7">
            <!-- Price Display -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-tag"></i> Harga Sewa</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $with_driver ? 'Dengan Sopir' : 'Tanpa Sopir' }}</h6>
                            <p class="text-muted mb-0">{{ $vehicle->category->name }}</p>
                        </div>
                        <div class="text-end">
                            <h5 class="text-primary mb-0">Rp {{ number_format($daily_price) }}</h5>
                            <small class="text-muted">/hari</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Pilih Tanggal Pemakaian</h5>
                </div>
                <div class="card-body">
                    @error('dates')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('start_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('rental_duration')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    
                    <!-- Date Inputs -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Tanggal Mulai *</strong></label>
                            <input type="date" 
                                   wire:model.live="start_date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Lama Pemakaian *</strong></label>
                            <div class="input-group">
                                <input type="number" 
                                       wire:model.live="rental_duration" 
                                       class="form-control @error('rental_duration') is-invalid @enderror" 
                                       min="1" 
                                       max="365" 
                                       required>
                                <span class="input-group-text">{{ $unitDisplay ?? 'hari' }}</span>
                                @error('rental_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    @if($start_date && $end_date)
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-calendar-check"></i> 
                        <strong>Periode Rental:</strong><br>
                        <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }}<br>
                        <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}<br>
                        <strong>Durasi:</strong> {{ $rental_duration }} {{ $unitDisplay ?? 'hari' }}
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Price Calculation -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-calculator"></i> Rincian Biaya</h5>
                </div>
                <div class="card-body">
                    @if($start_date && $end_date)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Durasi Rental:</span>
                            <strong>{{ $total_days }} {{ $unitDisplay ?? 'hari' }}</strong>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Harga per Hari:</span>
                            <strong>Rp {{ number_format($daily_price) }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total Biaya:</span>
                        <h4 class="text-primary mb-0">Rp {{ number_format($total_price) }}</h4>
                    </div>
                    @else
                    <p class="text-muted mb-0">Pilih tanggal pemakaian untuk melihat rincian biaya</p>
                    @endif
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="mt-4 d-flex justify-content-between">
                <button type="button" wire:click="previousStep" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" wire:click="nextStep" class="btn btn-primary btn-lg" 
                        {{ !$start_date || !$rental_duration || $rental_duration < 1 ? 'disabled' : '' }}>
                    Lanjut <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Step 3: Informasi Penyewa -->
    @if($currentStep == 3)
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informasi Penyewa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" wire:model="customer_name" class="form-control @error('customer_name') is-invalid @enderror" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" wire:model="customer_email" class="form-control @error('customer_email') is-invalid @enderror" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nomor WhatsApp *</label>
                            <input type="text" wire:model="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Alamat *</label>
                            <textarea wire:model="customer_address" class="form-control @error('customer_address') is-invalid @enderror" rows="3" required></textarea>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label">Catatan Tambahan (Optional)</label>
                            <textarea wire:model="notes" class="form-control" rows="3" placeholder="Catatan khusus atau permintaan tambahan..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" wire:click="previousStep" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" wire:click="nextStep" class="btn btn-primary">
                            Lanjut <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Step 4: Detil Booking -->
    @if($currentStep == 4)
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Detil Booking</h5>
                </div>
                <div class="card-body">
                    @if($errors->has('booking'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('booking') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Vehicle Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @php
                                $featuredImage = $vehicle->vehicleImages->where('is_featured', true)->first();
                                $firstImage = $featuredImage ?? $vehicle->vehicleImages->first();
                            @endphp
                            @if($firstImage)
                                <img src="{{ $firstImage->image_url }}" class="img-fluid rounded" alt="{{ $vehicle->name }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $vehicle->name }}</h4>
                            <p class="text-muted">{{ $vehicle->brand_name ?? $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>{{ $vehicle->category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kursi:</strong></td>
                                    <td>{{ $vehicle->seats }} kursi</td>
                                </tr>
                                <tr>
                                    <td><strong>Transmisi:</strong></td>
                                    <td>{{ $vehicle->transmission }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bahan Bakar:</strong></td>
                                    <td>{{ $vehicle->fuel_type }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Booking Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-calendar-alt"></i> Detail Booking</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Tanggal Mulai:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($start_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Selesai:</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Durasi:</strong></td>
                                    <td>{{ $total_days }} {{ $unitDisplay ?? 'hari' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Layanan:</strong></td>
                                    <td>{{ $with_driver ? 'Dengan Sopir' : 'Tanpa Sopir' }}</td>
                                </tr>
                                @if($rental_category_name)
                                <tr>
                                    <td><strong>Kategori Sewa:</strong></td>
                                    <td>{{ $rental_category_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3"><i class="fas fa-user"></i> Data Penyewa</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $customer_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customer_email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>WhatsApp:</strong></td>
                                    <td>{{ $customer_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $customer_address }}</td>
                                </tr>
                                @if($notes)
                                <tr>
                                    <td><strong>Catatan:</strong></td>
                                    <td>{{ $notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Price Summary -->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="mb-3"><i class="fas fa-calculator"></i> Rincian Biaya</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Harga per Hari:</td>
                                        <td class="text-end">Rp {{ number_format($daily_price) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Durasi Rental:</td>
                                        <td class="text-end">{{ $total_days }} {{ $unitDisplay }}</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Total Biaya:</strong></td>
                                        <td class="text-end"><strong>Rp {{ number_format($total_price) }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" wire:click="previousStep" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="button" wire:click="confirmBooking" class="btn btn-success btn-lg" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-paper-plane"></i> Kirim
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>

