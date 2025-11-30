@extends('layouts.member')

@section('title', $vehicle->name)
@section('page-title', 'Detail Kendaraan')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                @php
                    $featuredImage = $vehicle->vehicleImages->where('is_featured', true)->first();
                    $firstImage = $featuredImage ?? $vehicle->vehicleImages->first();
                @endphp
                @if($firstImage)
                    <img src="{{ $firstImage->image_url }}" class="img-fluid rounded mb-3" alt="{{ $vehicle->name }}" 
                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iODAwIiBoZWlnaHQ9IjQwMCIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0ic2Fucy1zZXJpZiIgZm9udC1zaXplPSIyNCIgZmlsbD0iIzk5OSIgZHk9Ii4zNWVtIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4=';">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded mb-3" style="height: 400px;">
                        <i class="fas fa-car fa-5x text-white"></i>
                    </div>
                @endif
                
                <h2>{{ $vehicle->name }}</h2>
                <p class="text-muted">
                    <i class="fas fa-tag"></i> {{ $vehicle->category->name ?? 'N/A' }}
                </p>
                
                <div class="mb-3">
                    <h5>Deskripsi</h5>
                    <p>{{ $vehicle->description ?? 'Tidak ada deskripsi.' }}</p>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar"></i> Tahun:</strong> {{ $vehicle->year ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-palette"></i> Warna:</strong> {{ $vehicle->color ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-gas-pump"></i> Bahan Bakar:</strong> {{ $vehicle->fuel_type ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-cog"></i> Transmisi:</strong> {{ $vehicle->transmission ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-chair"></i> Kursi:</strong> {{ $vehicle->seats ?? 'N/A' }}</p>
                        <p><strong><i class="fas fa-car-side"></i> Plat:</strong> {{ $vehicle->plate_number ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($vehicle->features && count($vehicle->features) > 0)
                    <div class="mb-3">
                        <h5>Fitur</h5>
                        <div class="row">
                            @foreach($vehicle->features as $feature)
                                <div class="col-md-6">
                                    <p><i class="fas fa-check text-success"></i> {{ $feature }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 80px;">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Harga Sewa</h5>
            </div>
            <div class="card-body text-center">
                <h2 class="text-info mb-3">
                    Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}
                </h2>
                <p class="text-muted">per hari</p>
                
                <a href="{{ route('booking.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-info btn-lg w-100 mb-3">
                    <i class="fas fa-calendar-check"></i> Booking Sekarang
                </a>
                
                <a href="{{ route('member.vehicles.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        
        @if($relatedVehicles->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-car"></i> Kendaraan Terkait</h6>
                </div>
                <div class="card-body">
                    @foreach($relatedVehicles as $related)
                        <div class="d-flex mb-3">
                            @php
                                $relatedFeatured = $related->vehicleImages->where('is_featured', true)->first();
                                $relatedFirst = $relatedFeatured ?? $related->vehicleImages->first();
                            @endphp
                            @if($relatedFirst)
                                <img src="{{ $relatedFirst->image_url }}" class="img-thumbnail me-2" style="width: 80px; height: 80px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjEwIiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+';">
                            @else
                                <div class="bg-secondary me-2 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-car text-white"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $related->name }}</h6>
                                <p class="text-muted mb-1 small">{{ $related->category->name ?? 'N/A' }}</p>
                                <p class="text-info mb-0"><strong>Rp {{ number_format($related->price_per_day, 0, ',', '.') }}/hari</strong></p>
                                <a href="{{ route('member.vehicles.show', $related->slug) }}" class="btn btn-sm btn-info mt-1">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

