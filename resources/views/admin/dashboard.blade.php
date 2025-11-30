@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row g-4 mb-4">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalVehicles }}</h4>
                        <p class="mb-0">Total Kendaraan</p>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-car"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>{{ $availableVehicles }} tersedia</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalBookings }}</h4>
                        <p class="mb-0">Total Booking</p>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>{{ $pendingBookings }} menunggu konfirmasi</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalArticles }}</h4>
                        <p class="mb-0">Total Artikel</p>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small>{{ $publishedArticles }} dipublikasi</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $totalCustomers }}</h4>
                        <p class="mb-0">Total Customer</p>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar"></i> Booking Terbaru</h5>
                <a href="{{ url('/admin/bookings') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th>Kendaraan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->booking_code }}</strong>
                                    </td>
                                    <td>
                                        {{ $booking->customer_name }}<br>
                                        <small class="text-muted">{{ $booking->customer_email }}</small>
                                    </td>
                                    <td>{{ $booking->vehicle->name }}</td>
                                    <td>
                                        {{ $booking->start_date->format('d/m/Y') }} - 
                                        {{ $booking->end_date->format('d/m/Y') }}
                                    </td>
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
                                    <td>Rp {{ number_format($booking->total_price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada booking</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ url('/admin/vehicles/create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kendaraan
                    </a>
                    <a href="{{ url('/admin/articles/create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tulis Artikel
                    </a>
                    <a href="{{ url('/admin/bookings?status=pending') }}" class="btn btn-warning">
                        <i class="fas fa-clock"></i> Booking Pending ({{ $pendingBookings }})
                    </a>
                    <a href="{{ url('/admin/availability') }}" class="btn btn-info">
                        <i class="fas fa-calendar-check"></i> Atur Ketersediaan
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Booking Chart -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik Booking Bulanan {{ date('Y') }}</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyBookingChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Booking Chart
    const ctx = document.getElementById('monthlyBookingChart').getContext('2d');
    const monthlyBookingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                label: 'Jumlah Booking',
                data: @json($monthlyBookings),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush