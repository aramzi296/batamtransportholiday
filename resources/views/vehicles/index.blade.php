@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')
<!-- Vehicles Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Daftar Kendaraan</h2>
            <p class="lead">Pilih kendaraan yang sesuai dengan kebutuhan perjalanan Anda</p>
        </div>
        
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Kendaraan</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('/vehicles') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Dengan Sopir</label>
                            <select name="driver_type" class="form-select">
                                <option value="">Semua</option>
                                <option value="with_driver" {{ request('driver_type') == 'with_driver' ? 'selected' : '' }}>Dengan Sopir</option>
                                <option value="without_driver" {{ request('driver_type') == 'without_driver' ? 'selected' : '' }}>Tanpa Sopir</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Merek</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Semua Merek</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" 
                                   placeholder="Cari model..." value="{{ request('model') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga Min (Rp)</label>
                            <input type="number" name="price_min" class="form-control" 
                                   placeholder="0" min="0" step="10000" value="{{ request('price_min') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Harga Max (Rp)</label>
                            <input type="number" name="price_max" class="form-control" 
                                   placeholder="0" min="0" step="10000" value="{{ request('price_max') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ url('/vehicles') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        @if($vehicles->count() > 0)
        <div class="row g-4">
            @foreach($vehicles as $vehicle)
            <div class="col-lg-4 col-md-6">
                <div class="card vehicle-card h-100">
                    <img src="{{ $vehicle->main_image }}" class="card-img-top" alt="{{ $vehicle->name }}" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicle->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge bg-primary">{{ $vehicle->category->name }}</span>
                                @if(isset($vehicle->price_type) && $vehicle->price_type === 'with_driver')
                                    <span class="badge bg-success ms-1">Dengan Sopir</span>
                                @elseif(isset($vehicle->price_type) && $vehicle->price_type === 'without_driver')
                                    <span class="badge bg-info ms-1">Tanpa Sopir</span>
                                @endif
                            </div>
                            <h4 class="text-primary mb-0">Rp {{ number_format($vehicle->display_price ?? $vehicle->price_per_day) }}/hari</h4>
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-3">
                                <small class="text-muted">
                                    <i class="fas fa-users"></i><br>
                                    {{ $vehicle->seats }} Kursi
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-muted">
                                    <i class="fas fa-cog"></i><br>
                                    {{ $vehicle->transmission }}
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-muted">
                                    <i class="fas fa-gas-pump"></i><br>
                                    {{ $vehicle->fuel_type }}
                                </small>
                            </div>
                            <div class="col-3">
                                <small class="text-muted">
                                    <i class="fas fa-palette"></i><br>
                                    {{ $vehicle->color }}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('vehicles.show', $vehicle->slug) }}" class="btn btn-outline-primary flex-fill">
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                            <a href="{{ url('/booking?vehicle_id=' . $vehicle->id) }}" class="btn btn-primary flex-fill">
                                <i class="fas fa-calendar-check"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12 d-flex justify-content-center">
                {{ $vehicles->links() }}
            </div>
        </div>
        @else
        <!-- No Results -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-car fa-4x text-muted"></i>
            </div>
            <h3>Tidak ada kendaraan yang ditemukan</h3>
            <p class="text-muted">Belum ada kendaraan yang tersedia saat ini.</p>
        </div>
        @endif
    </div>
</section>
@endsection