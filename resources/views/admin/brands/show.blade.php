@extends('layouts.admin')

@section('title', 'Detail Merek')
@section('page-title', 'Detail Merek')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Merek: {{ $brand->name }}</h5>
        <div>
            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th width="200">ID</th>
                        <td><code>#{{ $brand->id }}</code></td>
                    </tr>
                    <tr>
                        <th>Nama Merek</th>
                        <td><strong>{{ $brand->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td><code>{{ $brand->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($brand->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jumlah Kendaraan</th>
                        <td>
                            <span class="badge bg-primary rounded-pill">
                                {{ $brand->vehicles->count() }} kendaraan
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($brand->vehicles->count() > 0)
        <hr>
        <h6 class="mb-3">Daftar Kendaraan dengan Merek Ini</h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Nama Kendaraan</th>
                        <th>Model</th>
                        <th>Tahun</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brand->vehicles as $vehicle)
                    <tr>
                        <td>
                            <a href="{{ route('admin.vehicles.show', $vehicle) }}">
                                {{ $vehicle->name }}
                            </a>
                        </td>
                        <td>{{ $vehicle->model }}</td>
                        <td>{{ $vehicle->year }}</td>
                        <td>
                            @if($vehicle->is_available)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection



