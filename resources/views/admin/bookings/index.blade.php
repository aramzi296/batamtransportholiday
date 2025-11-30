@extends('layouts.admin')

@section('title', 'Manajemen Booking')
@section('page-title', 'Manajemen Booking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Booking</h4>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download"></i> Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel"></i> Export Excel</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf"></i> Export PDF</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Kode booking, nama, email...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="vehicle_id" class="form-label">Kendaraan</label>
                    <select class="form-select" id="vehicle_id" name="vehicle_id">
                        <option value="">Semua Kendaraan</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Content Card -->
<div class="card">
    <div class="card-body">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tanggal Rental</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $booking->booking_code }}</strong><br>
                                <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->customer_name }}</strong><br>
                                <small class="text-muted">{{ $booking->customer_email }}</small><br>
                                <small class="text-muted">{{ $booking->customer_phone }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->vehicle->name }}</strong><br>
                                <small class="text-muted">{{ $booking->vehicle->plate_number }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->start_date->format('d/m/Y') }}</strong><br>
                                <small class="text-muted">s/d {{ $booking->end_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $booking->total_days }} hari</span>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($booking->total_price) }}</strong><br>
                                <small class="text-muted">Rp {{ number_format($booking->daily_price) }}/hari</small>
                            </td>
                            <td>
                                @switch($booking->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Pending</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-primary">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Konfirmasi booking ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Batalkan booking ini?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5>Belum Ada Booking</h5>
                <p class="text-muted">Belum ada booking yang masuk ke sistem.</p>
            </div>
        @endif
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $bookings->where('status', 'pending')->count() }}</h5>
                        <small>Pending</small>
                    </div>
                    <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $bookings->where('status', 'confirmed')->count() }}</h5>
                        <small>Dikonfirmasi</small>
                    </div>
                    <i class="fas fa-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $bookings->where('status', 'completed')->count() }}</h5>
                        <small>Selesai</small>
                    </div>
                    <i class="fas fa-flag-checkered fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>{{ $bookings->where('status', 'cancelled')->count() }}</h5>
                        <small>Dibatalkan</small>
                    </div>
                    <i class="fas fa-times fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endpush