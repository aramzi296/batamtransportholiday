@extends('layouts.app')

@section('title', 'Booking ' . $vehicle->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/vehicles') }}">Kendaraan</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/vehicles/' . $vehicle->slug) }}">{{ $vehicle->name }}</a></li>
            <li class="breadcrumb-item active">Booking</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Vehicle Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <img src="{{ $vehicle->main_image }}" class="card-img-top" alt="{{ $vehicle->name }}" 
                     style="height: 200px; object-fit: cover;"
                     onerror="this.onerror=null; this.src='{{ asset('/images/vehicles/default.jpg') }}';">
                <div class="card-body">
                    <h5 class="card-title">{{ $vehicle->name }}</h5>
                    <p class="card-text text-muted mb-3">{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-users"></i><br>
                                {{ $vehicle->seats }} Kursi
                            </small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">
                                <i class="fas fa-cog"></i><br>
                                {{ $vehicle->transmission }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Price Information -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="mb-3 text-center">
                            <i class="fas fa-calculator text-primary"></i> Rincian Biaya
                        </h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Durasi Rental</span>
                                <span class="fw-semibold">{{ $totalDays }} hari</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Harga per Hari</span>
                                <span>Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-6">Total Biaya</span>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <small class="text-muted d-block mt-2 text-center">
                            <i class="fas fa-info-circle"></i> Harga sudah termasuk asuransi
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Form Booking</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{!! $error !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!empty($availabilityWarnings))
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Peringatan Ketersediaan</h6>
                            <p class="mb-2">Kendaraan tidak tersedia pada tanggal berikut:</p>
                            <ul class="mb-0">
                                @foreach ($availabilityWarnings as $warning)
                                    <li><strong>{{ $warning['date'] }}</strong> - {{ $warning['reason'] }}</li>
                                @endforeach
                            </ul>
                            <p class="mb-0 mt-2"><small class="text-muted">Silakan pilih tanggal lain atau hubungi kami untuk informasi lebih lanjut.</small></p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('booking.store') }}">
                        @csrf
                        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                        
                        <!-- Booking Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                       value="{{ $startDate }}" required min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                       value="{{ $endDate }}" required min="{{ date('Y-m-d') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Customer Information -->
                        <h5 class="mb-3"><i class="fas fa-user"></i> Informasi Penyewa</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                       value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon *</label>
                                <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       value="{{ old('customer_phone') }}" required placeholder="08xxxxxxxxxx">
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat *</label>
                                <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" 
                                          rows="3" required placeholder="Alamat lengkap">{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3" 
                                      placeholder="Keperluan khusus, permintaan tambahan, dll.">{{ old('notes') }}</textarea>
                        </div>

                        <hr>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Syarat dan Ketentuan</h5>
                            <div class="bg-light p-3 rounded">
                                <ul class="mb-0 small">
                                    <li>Booking akan dikonfirmasi dalam waktu 24 jam</li>
                                    <li>Pembayaran dilakukan setelah booking dikonfirmasi</li>
                                    <li>Penyewa harus memiliki SIM yang masih berlaku</li>
                                    <li>Deposit akan dikembalikan setelah kendaraan dikembalikan dalam kondisi baik</li>
                                    <li>Keterlambatan pengembalian dikenakan denda sesuai ketentuan</li>
                                    <li>Biaya BBM ditanggung oleh penyewa</li>
                                </ul>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="agree_terms" required>
                                <label class="form-check-label" for="agree_terms">
                                    Saya setuju dengan <a href="{{ route('terms') }}" target="_blank">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ url('/vehicles/' . $vehicle->slug) }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane"></i> Kirim Booking
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-update price when dates change
    document.querySelector('input[name="start_date"]').addEventListener('change', updateBooking);
    document.querySelector('input[name="end_date"]').addEventListener('change', updateBooking);
    
    function updateBooking() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        
        if (startDate && endDate) {
            // Redirect to update the calculation
            const url = new URL(window.location.href);
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);
            window.location.href = url.toString();
        }
    }
    
    // Prevent form submission if there are availability warnings
    document.querySelector('form').addEventListener('submit', function(e) {
        const warningAlert = document.querySelector('.alert-warning');
        if (warningAlert) {
            e.preventDefault();
            alert('Tidak dapat melakukan booking karena kendaraan tidak tersedia pada beberapa tanggal yang dipilih. Silakan pilih tanggal lain.');
            return false;
        }
    });
</script>
@endpush