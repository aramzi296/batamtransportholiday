@extends('layouts.admin')

@section('title', 'Detail Kendaraan')
@section('page-title', 'Detail Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>{{ $vehicle->name }}</h4>
    <div>
        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Vehicle Images -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-images"></i> Galeri Foto</h5>
            </div>
            <div class="card-body p-0">
                @if($vehicle->images && count($vehicle->images) > 0)
                    <div id="vehicleCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($vehicle->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ $image }}" class="d-block w-100" alt="{{ $vehicle->name }}" 
                                     style="height: 400px; object-fit: cover;">
                            </div>
                            @endforeach
                        </div>
                        @if(count($vehicle->images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#vehicleCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-image fa-4x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada gambar</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Vehicle Description -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-align-left"></i> Deskripsi</h5>
            </div>
            <div class="card-body">
                <p>{{ $vehicle->description }}</p>
            </div>
        </div>

        <!-- Features -->
        @if($vehicle->features && count($vehicle->features) > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-star"></i> Fitur & Fasilitas</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($vehicle->features as $feature)
                        <span class="badge bg-primary fs-6">{{ $feature }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Vehicle Info -->
    <div class="col-lg-4">
        <!-- Basic Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Dasar</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Kategori:</strong></td>
                        <td><span class="badge bg-primary">{{ $vehicle->category->name }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Harga/Hari:</strong></td>
                        <td><span class="text-success fw-bold">Rp {{ number_format($vehicle->price_per_day) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($vehicle->is_available)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Plat Nomor:</strong></td>
                        <td><code>{{ $vehicle->plate_number }}</code></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Specifications -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i> Spesifikasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Merk:</strong></td>
                        <td>{{ $vehicle->brand }}</td>
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
                        <td><strong>Warna:</strong></td>
                        <td>{{ $vehicle->color }}</td>
                    </tr>
                    <tr>
                        <td><strong>Bahan Bakar:</strong></td>
                        <td>{{ $vehicle->fuel_type }}</td>
                    </tr>
                    <tr>
                        <td><strong>Transmisi:</strong></td>
                        <td>{{ $vehicle->transmission }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Kursi:</strong></td>
                        <td>{{ $vehicle->seats }} kursi</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ url('/vehicles/' . $vehicle->slug) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Lihat di Website
                    </a>
                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Kendaraan
                    </a>
                    @if($vehicle->is_available)
                        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $vehicle->name }}">
                            <input type="hidden" name="category_id" value="{{ $vehicle->category_id }}">
                            <input type="hidden" name="description" value="{{ $vehicle->description }}">
                            <input type="hidden" name="price_per_day" value="{{ $vehicle->price_per_day }}">
                            <input type="hidden" name="brand" value="{{ $vehicle->brand }}">
                            <input type="hidden" name="model" value="{{ $vehicle->model }}">
                            <input type="hidden" name="year" value="{{ $vehicle->year }}">
                            <input type="hidden" name="color" value="{{ $vehicle->color }}">
                            <input type="hidden" name="fuel_type" value="{{ $vehicle->fuel_type }}">
                            <input type="hidden" name="transmission" value="{{ $vehicle->transmission }}">
                            <input type="hidden" name="seats" value="{{ $vehicle->seats }}">
                            <input type="hidden" name="plate_number" value="{{ $vehicle->plate_number }}">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-pause"></i> Set Tidak Tersedia
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $vehicle->name }}">
                            <input type="hidden" name="category_id" value="{{ $vehicle->category_id }}">
                            <input type="hidden" name="description" value="{{ $vehicle->description }}">
                            <input type="hidden" name="price_per_day" value="{{ $vehicle->price_per_day }}">
                            <input type="hidden" name="brand" value="{{ $vehicle->brand }}">
                            <input type="hidden" name="model" value="{{ $vehicle->model }}">
                            <input type="hidden" name="year" value="{{ $vehicle->year }}">
                            <input type="hidden" name="color" value="{{ $vehicle->color }}">
                            <input type="hidden" name="fuel_type" value="{{ $vehicle->fuel_type }}">
                            <input type="hidden" name="transmission" value="{{ $vehicle->transmission }}">
                            <input type="hidden" name="seats" value="{{ $vehicle->seats }}">
                            <input type="hidden" name="plate_number" value="{{ $vehicle->plate_number }}">
                            <input type="hidden" name="is_available" value="1">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-play"></i> Set Tersedia
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking History -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Booking</h5>
                <span class="badge bg-info">{{ $vehicle->bookings->count() }} Total</span>
            </div>
            <div class="card-body">
                @if($vehicle->bookings && $vehicle->bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Customer</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicle->bookings->take(5) as $booking)
                                <tr>
                                    <td><code>{{ $booking->booking_code }}</code></td>
                                    <td>
                                        {{ $booking->customer_name }}<br>
                                        <small class="text-muted">{{ $booking->customer_email }}</small>
                                    </td>
                                    <td>
                                        {{ $booking->start_date->format('d/m/Y') }} - 
                                        {{ $booking->end_date->format('d/m/Y') }}
                                    </td>
                                    <td>Rp {{ number_format($booking->total_price) }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning">Menunggu</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-success">Dikonfirmasi</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Dibatalkan</span>
                                        @else
                                            <span class="badge bg-info">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($vehicle->bookings->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('admin.bookings.index') }}?vehicle_id={{ $vehicle->id }}" class="btn btn-outline-primary">
                                Lihat Semua Booking ({{ $vehicle->bookings->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada riwayat booking untuk kendaraan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection