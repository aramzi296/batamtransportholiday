@extends('layouts.member')

@section('title', 'Dashboard Member')
@section('page-title', 'Dashboard Member')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Daftar Order Booking</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('member.dashboard') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Open</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Konfirmasi</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filter Kendaraan</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">Semua Kendaraan</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->name }} - {{ $vehicle->plate_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Thumbnail</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Nomor Kendaraan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    @php
                                        $featuredImage = $booking->vehicle->vehicleImages->where('is_featured', true)->first();
                                        $firstImage = $featuredImage ?? $booking->vehicle->vehicleImages->first();
                                        $startDate = \Carbon\Carbon::parse($booking->start_date);
                                        $endDate = \Carbon\Carbon::parse($booking->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                        
                                        // Mapping status
                                        $statusLabels = [
                                            'pending' => 'Open',
                                            'confirmed' => 'Konfirmasi',
                                            'cancelled' => 'Batal',
                                            'completed' => 'Selesai'
                                        ];
                                        $statusBadges = [
                                            'pending' => 'warning',
                                            'confirmed' => 'success',
                                            'cancelled' => 'danger',
                                            'completed' => 'info'
                                        ];
                                        $statusLabel = $statusLabels[$booking->status] ?? $booking->status;
                                        $statusBadge = $statusBadges[$booking->status] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($firstImage)
                                                <img src="{{ $firstImage->image_url }}" alt="{{ $booking->vehicle->name }}" 
                                                     class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjEwIiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+';">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-car text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $booking->vehicle->name }}</strong><br>
                                            <small class="text-muted">{{ $booking->vehicle->category->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $booking->vehicle->plate_number }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $startDate->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $endDate->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $duration }} {{ $duration == 1 ? 'Hari' : 'Hari' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusBadge }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Belum ada order booking yang diterima.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
