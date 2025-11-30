@extends('layouts.app')

@section('title', 'Daftar Kendaraan')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="display-6 fw-bold">Daftar Kendaraan</h1>
            <p class="lead text-muted">Pilih kendaraan yang sesuai dengan kebutuhan perjalanan Anda</p>
        </div>
        <div class="col-lg-4">
            <div class="d-flex justify-content-lg-end">
                <span class="badge bg-primary fs-6">
                    {{ $vehicles->total() }} Kendaraan Tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filter & Pencarian</h5>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Kategori</label>
                                <select name="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" 
                                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ request('start_date') }}" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="{{ request('end_date') }}" min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Pencarian</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Nama, brand, model..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutkan</label>
                                <select name="sort" class="form-select">
                                    <option value="">Terbaru</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                        Harga: Rendah ke Tinggi
                                    </option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                        Harga: Tinggi ke Rendah
                                    </option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                        Nama A-Z
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehicles Grid -->
    @if($vehicles->count() > 0)
        <div class="row g-4">
            @foreach($vehicles as $vehicle)
            <div class="col-lg-4 col-md-6">
                <div class="card vehicle-card h-100">
                    <img src="{{ $vehicle->main_image }}" class="card-img-top" alt="{{ $vehicle->name }}" 
                         style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $vehicle->name }}</h5>
                            <span class="badge bg-primary">{{ $vehicle->category->name }}</span>
                        </div>
                        <p class="text-muted mb-2">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                        
                        <!-- Vehicle Info -->
                        <div class="row text-center mb-3">
                            <div class="col-3">
                                <small class="text-muted">
                                    <i class="fas fa-users"></i><br>
                                    {{ $vehicle->seats }}
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

                        <!-- Features -->
                        @if($vehicle->features && count($vehicle->features) > 0)
                        <div class="mb-3">
                            <small class="text-muted">Fitur:</small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach(array_slice($vehicle->features, 0, 3) as $feature)
                                    <span class="badge bg-light text-dark">{{ $feature }}</span>
                                @endforeach
                                @if(count($vehicle->features) > 3)
                                    <span class="badge bg-light text-dark">+{{ count($vehicle->features) - 3 }} lainnya</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Price -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="text-primary mb-0">Rp {{ number_format($vehicle->price_per_day) }}</h4>
                                <small class="text-muted">per hari</small>
                            </div>
                            <div class="text-end">
                                @if($vehicle->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="{{ url('/vehicles/' . $vehicle->slug) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-5">
            <div class="col-12">
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
            <p class="text-muted">Silakan ubah filter pencarian atau coba kata kunci lain.</p>
            <a href="{{ url('/vehicles') }}" class="btn btn-primary">
                <i class="fas fa-refresh"></i> Reset Pencarian
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-set end date when start date changes
    document.querySelector('input[name="start_date"]').addEventListener('change', function() {
        const startDate = new Date(this.value);
        const endDateInput = document.querySelector('input[name="end_date"]');
        
        if (endDateInput.value === '' || new Date(endDateInput.value) <= startDate) {
            const nextDay = new Date(startDate);
            nextDay.setDate(nextDay.getDate() + 1);
            endDateInput.value = nextDay.toISOString().split('T')[0];
        }
        
        endDateInput.setAttribute('min', this.value);
    });
</script>
@endpush