@extends('layouts.admin')

@section('title', 'Kalender Kendaraan: ' . $vehicle->name)

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Kalender Kendaraan: {{ $vehicle->name }}</h3>
    <a href="{{ route('admin.vehicles.calendar.block', $vehicle->id) }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Blokir Tanggal
    </a>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Alasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calendar as $item)
                    <tr>
                        <td>{{ \\Carbon\\Carbon::parse($item->date)->format('d M Y') }}</td>
                        <td>
                            @if ($item->blocked_by == 'booking')
                                <span class="badge bg-warning text-dark">Dibooking</span>
                            @else
                                <span class="badge bg-danger">Blok Admin</span>
                            @endif
                        </td>
                        <td>{{ $item->reason ?? '-' }}</td>
                        <td>
                            @if ($item->blocked_by == 'admin')
                                <form method="POST" action="{{ route('admin.vehicles.calendar.block.delete', [$vehicle->id, $item->id]) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus blok tanggal ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada tanggal yang diblokir.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
