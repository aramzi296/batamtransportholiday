@extends('layouts.member')

@section('title', 'Daftar Kendaraan')
@section('page-title', 'Daftar Kendaraan')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-car"></i> Daftar Kendaraan Saya</h5>
                <a href="{{ route('member.vehicles.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kendaraan
                </a>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('member.vehicles.index') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label class="form-label">Cari Kendaraan</label>
                            <input type="text" name="search" class="form-control" placeholder="Nama, brand, model, atau plat nomor..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @forelse($vehicles as $vehicle)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @php
                                    $featuredImage = $vehicle->vehicleImages->where('is_featured', true)->first();
                                    $firstImage = $featuredImage ?? $vehicle->vehicleImages->first();
                                @endphp
                                @if($firstImage)
                                    <img src="{{ $firstImage->image_url }}" class="card-img-top" alt="{{ $vehicle->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-car fa-3x text-white"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $vehicle->name }}</h5>
                                        @if($vehicle->is_available)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                        @endif
                                    </div>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-tag"></i> {{ $vehicle->category->name ?? 'N/A' }}
                                    </p>
                                    <p class="card-text mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> {{ $vehicle->year ?? 'N/A' }} | 
                                            <i class="fas fa-palette"></i> {{ $vehicle->color ?? 'N/A' }} | 
                                            <i class="fas fa-chair"></i> {{ $vehicle->seats ?? 'N/A' }} Kursi
                                        </small>
                                    </p>
                                    <p class="mb-2">
                                        <strong class="text-info">
                                            Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}/hari
                                        </strong>
                                    </p>
                                    <p class="mb-2">
                                        <small class="text-muted">
                                            <i class="fas fa-images"></i> {{ $vehicle->vehicleImages->count() }} Foto
                                        </small>
                                    </p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('member.vehicles.edit', $vehicle) }}" class="btn btn-warning btn-sm flex-fill">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('member.vehicles.destroy', $vehicle) }}" method="POST" class="flex-fill" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Belum ada kendaraan yang didaftarkan.
                                <br>
                                <a href="{{ route('member.vehicles.create') }}" class="btn btn-info mt-2">
                                    <i class="fas fa-plus"></i> Tambah Kendaraan Pertama
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $vehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

