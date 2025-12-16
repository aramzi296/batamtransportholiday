<div>
    <style>
        /* Filter Section Styling - Sidebar */
        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: sticky;
            top: 90px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
        }
        
        .filter-section .card-header {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            padding: 1.25rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .filter-section .card-header h5 {
            color: #667eea;
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }
        
        .filter-section .card-body {
            background: rgba(255, 255, 255, 0.98);
            padding: 1.5rem;
        }
        
        .filter-section .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .filter-section .form-select,
        .filter-section .form-control {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .filter-section .form-select:focus,
        .filter-section .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .filter-section .btn-outline-secondary {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.9rem;
        }
        
        .filter-section .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        /* Vehicles Content Area */
        .vehicles-content {
            min-height: 500px;
        }
        
        @media (max-width: 991.98px) {
            .filter-section {
                position: relative;
                top: 0;
                max-height: none;
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .filter-section .card-body {
                padding: 1.25rem;
            }
        }
        
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
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Category Header Styling */
        .category-header {
            border-radius: 12px;
            overflow: hidden;
        }
        
        /* Rental Category Name Styling */
        .rental-category-name {
            color: #28a745;
            font-weight: 600;
            font-size: 0.85em;
            margin-left: 0.5rem;
        }
    </style>
    
    <!-- Loading Indicator -->
    <div wire:loading class="text-center py-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
        <p class="mt-2 text-muted">Memuat armada...</p>
    </div>
    
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card filter-section">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Seleksi Armada</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <!-- 1. Dengan Sopir -->
                        <div>
                            <label class="form-label">Dengan Sopir</label>
                            <select wire:model.live="driver_type" class="form-select">
                                <option value="">Semua</option>
                                <option value="with_driver">Dengan Sopir</option>
                                <option value="without_driver">Tanpa Sopir</option>
                            </select>
                        </div>

                        <!-- 2. Max Penumpang -->
                        <div>
                            <label class="form-label">Max Penumpang</label>
                            <select wire:model.live="seats" class="form-select">
                                <option value="">Semua</option>
                                @foreach($seatsOptions as $seatsValue)
                                    <option value="{{ $seatsValue }}">{{ $seatsValue }} Penumpang</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. Kategori -->
                        <div>
                            <label class="form-label">Kategori</label>
                            <select wire:model.live="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 4. Merek -->
                        <div>
                            <label class="form-label">Merek</label>
                            <select wire:model.live="brand_id" class="form-select">
                                <option value="">Semua Merek</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 5. Urutkan -->
                        <div>
                            <label class="form-label">Urutkan</label>
                            <select wire:model.live="sort" class="form-select">
                                <option value="price_low">Harga: Rendah ke Tinggi</option>
                                <option value="price_high">Harga: Tinggi ke Rendah</option>
                                <option value="name">Nama: A-Z</option>
                            </select>
                        </div>
                        <div>
                            <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-redo"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vehicles Content -->
        <div class="col-lg-9 col-md-8 vehicles-content">
            @if($vehicles->count() > 0)
            <div wire:loading.remove class="row g-4">
                @foreach($vehicles as $vehicle)
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
                                    Rp. {{ number_format($vehicle->display_price) }} /hari
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
                            
                            <!-- Baris 5: Button Booking -->
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
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-primary w-100">
                                <i class="fab fa-whatsapp"></i> Booking Sekarang
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div wire:loading.remove class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $vehicles->links('livewire.pagination.bootstrap-5') }}
                </div>
            </div>
            @else
            <!-- No Results -->
            <div wire:loading.remove class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-car fa-4x text-muted"></i>
                </div>
                <h3>Tidak ada kendaraan yang ditemukan</h3>
                <p class="text-muted">Coba ubah filter untuk menemukan kendaraan yang sesuai.</p>
            </div>
            @endif
        </div>
    </div>
</div>
