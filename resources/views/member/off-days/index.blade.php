@extends('layouts.member')

@section('title', 'Hari Off')
@section('page-title', 'Hari Off Kendaraan')

@section('content')
<div class="row mb-3">
    <div class="col-12 text-end">
        <a href="{{ route('member.off-days.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Hari Off
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-times"></i> Daftar Hari Off Kendaraan</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="GET" action="{{ route('member.off-days.index') }}" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Filter Kendaraan</label>
                            <select name="vehicle_id" class="form-select">
                                <option value="">Semua Kendaraan</option>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}" {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->name }} - {{ $vehicle->plate_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if($offDays->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Thumbnail</th>
                                    <th>Nama Kendaraan</th>
                                    <th>Nomor Kendaraan</th>
                                    <th>Tanggal Off</th>
                                    <th>Durasi</th>
                                    <th>Alasan Off</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($offDays as $offDay)
                                    @php
                                        $featuredImage = $offDay->vehicle->vehicleImages->where('is_featured', true)->first();
                                        $firstImage = $featuredImage ?? $offDay->vehicle->vehicleImages->first();
                                        $startDate = \Carbon\Carbon::parse($offDay->start_date);
                                        $endDate = \Carbon\Carbon::parse($offDay->end_date);
                                        $duration = $startDate->diffInDays($endDate) + 1;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($firstImage)
                                                <img src="{{ $firstImage->image_url }}" alt="{{ $offDay->vehicle->name }}" 
                                                     class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIiBmb250LXNpemU9IjEwIiBmaWxsPSIjOTk5IiBkeT0iLjNlbSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+';">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-car text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $offDay->vehicle->name }}</strong><br>
                                            <small class="text-muted">{{ $offDay->vehicle->category->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $offDay->vehicle->plate_number }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $startDate->format('d/m/Y') }}</strong><br>
                                            <small class="text-muted">sampai</small><br>
                                            <strong>{{ $endDate->format('d/m/Y') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">
                                                {{ $duration }} {{ $duration == 1 ? 'Hari' : 'Hari' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($offDay->reason)
                                                <p class="mb-0">{{ $offDay->reason }}</p>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('member.off-days.edit', $offDay->id) }}" 
                                                   class="btn btn-warning btn-sm" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('member.off-days.destroy', $offDay->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari off ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
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

                    <div class="d-flex justify-content-center mt-4">
                        {{ $offDays->links() }}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Tidak ada kendaraan yang sedang off.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

