@extends('layouts.admin')

@section('title', 'Manajemen Ketersediaan')
@section('page-title', 'Manajemen Ketersediaan Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Ketersediaan Kendaraan</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
        <i class="fas fa-plus"></i> Tambah Ketersediaan
    </button>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.availability.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="vehicle_id" class="form-label">Kendaraan</label>
                    <select class="form-select" id="vehicle_id" name="vehicle_id">
                        <option value="">Semua Kendaraan</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->name }} ({{ $vehicle->plate_number }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="is_available" class="form-label">Status</label>
                    <select class="form-select" id="is_available" name="is_available">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('is_available') === '1' ? 'selected' : '' }}>Tersedia</option>
                        <option value="0" {{ request('is_available') === '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.availability.index') }}" class="btn btn-outline-secondary">
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
        @if($availabilities->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kendaraan</th>
                            <th>Status</th>
                            <th>Alasan</th>
                            <th>Dibuat</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availabilities as $availability)
                        <tr>
                            <td>
                                <strong>{{ $availability->date->format('d/m/Y') }}</strong><br>
                                <small class="text-muted">{{ $availability->date->format('l') }}</small>
                            </td>
                            <td>
                                <strong>{{ $availability->vehicle->name }}</strong><br>
                                <small class="text-muted">{{ $availability->vehicle->plate_number }}</small>
                            </td>
                            <td>
                                @if($availability->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td>
                                {{ $availability->reason ?: '-' }}
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $availability->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td>
                                <form action="{{ route('admin.availability.destroy', $availability->id) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ketersediaan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $availabilities->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h5>Belum Ada Data Ketersediaan</h5>
                <p class="text-muted">Mulai atur ketersediaan kendaraan untuk tanggal-tanggal tertentu.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAvailabilityModal">
                    <i class="fas fa-plus"></i> Tambah Ketersediaan Pertama
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Availability Modal -->
<div class="modal fade" id="addAvailabilityModal" tabindex="-1" aria-labelledby="addAvailabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.availability.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAvailabilityModalLabel">Tambah Ketersediaan Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_vehicle_id" class="form-label">Kendaraan <span class="text-danger">*</span></label>
                        <select class="form-select" id="modal_vehicle_id" name="vehicle_id" required>
                            <option value="">Pilih Kendaraan</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">
                                    {{ $vehicle->name }} ({{ $vehicle->plate_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="modal_date" name="date" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_is_available" class="form-label">Status Ketersediaan <span class="text-danger">*</span></label>
                        <select class="form-select" id="modal_is_available" name="is_available" required>
                            <option value="">Pilih Status</option>
                            <option value="1">Tersedia</option>
                            <option value="0">Tidak Tersedia</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_reason" class="form-label">Alasan (opsional)</label>
                        <textarea class="form-control" id="modal_reason" name="reason" rows="3" 
                                  placeholder="Masukkan alasan jika tidak tersedia (misal: maintenance, booking pribadi, dll)"></textarea>
                        <div class="form-text">Alasan akan membantu dalam pengelolaan ketersediaan kendaraan</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
    
    // Show reason field only when not available is selected
    document.getElementById('modal_is_available').addEventListener('change', function() {
        const reasonGroup = document.getElementById('modal_reason').closest('.mb-3');
        if (this.value === '0') {
            reasonGroup.style.display = 'block';
            document.getElementById('modal_reason').setAttribute('required', true);
        } else {
            reasonGroup.style.display = 'block'; // Always show for consistency
            document.getElementById('modal_reason').removeAttribute('required');
        }
    });
</script>
@endpush