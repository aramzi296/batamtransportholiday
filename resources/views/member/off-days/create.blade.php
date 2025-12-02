@extends('layouts.member')

@section('title', 'Tambah Hari Off')
@section('page-title', 'Tambah Hari Off Kendaraan')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-plus"></i> Tambah Hari Off Kendaraan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('member.off-days.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="vehicle_id" class="form-label">Pilih Kendaraan <span class="text-danger">*</span></label>
                        <select name="vehicle_id" id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->name }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($vehicles->count() == 0)
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Anda belum memiliki kendaraan. 
                                <a href="{{ route('member.vehicles.create') }}">Tambah kendaraan terlebih dahulu</a>.
                            </small>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai Off <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date') }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">Tanggal Akhir Off <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date') }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('dates')
                                <div class="text-danger small mt-1">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Off</label>
                        <textarea name="reason" id="reason" rows="4" 
                                  class="form-control @error('reason') is-invalid @enderror" 
                                  placeholder="Masukkan alasan kendaraan off (opsional)">{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maksimal 500 karakter</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('member.off-days.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan Hari Off
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set minimum end_date berdasarkan start_date
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('end_date');
        if (startDate) {
            endDateInput.min = startDate;
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        }
    });
</script>
@endpush
@endsection






