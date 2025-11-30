@extends('layouts.admin')

@section('title', 'Daftar Kendaraan')
@section('page-title', 'Manajemen Kendaraan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Kendaraan</h4>
    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kendaraan
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($vehicles->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Kendaraan</th>
                            <th>Kategori</th>
                            <th>Merk/Model</th>
                            <th>Harga/Hari</th>
                            <th>Status</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $vehicle)
                        @php
                            $featuredImage = $vehicle->vehicleImages->where('is_featured', true)->first();
                            $firstImage = $featuredImage ?? $vehicle->vehicleImages->first();
                        @endphp
                        <tr>
                            <td>
                                @if($firstImage)
                                    <img src="{{ $firstImage->image_url }}" alt="{{ $vehicle->name }}" 
                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjYwIiBoZWlnaHQ9IjYwIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjgiIGZpbGw9IiM5OTkiIGR5PSIuM2VtIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4=';">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center img-thumbnail" style="width: 60px; height: 60px;">
                                        <i class="fas fa-car text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $vehicle->name }}</strong><br>
                                <small class="text-muted">{{ $vehicle->plate_number }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $vehicle->category->name }}</span>
                            </td>
                            <td>
                                {{ $vehicle->brand }} {{ $vehicle->model }}<br>
                                <small class="text-muted">{{ $vehicle->year }} • {{ $vehicle->seats }} kursi</small>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($vehicle->price_per_day) }}</strong>
                            </td>
                            <td>
                                @if($vehicle->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.vehicles.destroy', $vehicle) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $vehicles->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-car fa-4x text-muted mb-3"></i>
                <h5>Belum Ada Kendaraan</h5>
                <p class="text-muted">Mulai tambahkan kendaraan untuk disewakan.</p>
                <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kendaraan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection