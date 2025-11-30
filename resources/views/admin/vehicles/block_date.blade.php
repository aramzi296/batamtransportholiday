@extends('layouts.admin')

@section('title', 'Blokir Tanggal Kendaraan: ' . $vehicle->name)

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Blokir Tanggal Kendaraan: {{ $vehicle->name }}</h3>
    <form method="POST" action="{{ route('admin.vehicles.calendar.block.store', $vehicle->id) }}" class="card p-4 shadow-sm" style="max-width:400px;">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Tanggal</label>
            <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Alasan Blokir</label>
            <input type="text" id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" required>
            @error('reason')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-ban"></i> Blokir Tanggal
            </button>
        </div>
    </form>
    <a href="{{ route('admin.vehicles.calendar', $vehicle->id) }}" class="btn btn-link mt-3">&laquo; Kembali ke Kalender</a>
</div>
@endsection
