@extends('layouts.admin')

@section('title', 'Manajemen Testimoni')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Manajemen Testimoni</h1>
                <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Testimoni
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.testimonials.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Cari</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Nama, lokasi, atau pesan..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Unggulan</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select">
                                    <option value="">Semua Rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} Bintang
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Testimonials Table -->
            <div class="card">
                <div class="card-body">
                    @if($testimonials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Foto</th>
                                        <th>Nama & Lokasi</th>
                                        <th style="width: 120px;">Rating</th>
                                        <th>Pesan</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">Urutan</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testimonials as $testimonial)
                                        <tr>
                                            <td>
                                                <img src="{{ $testimonial->photo_url }}" 
                                                     class="rounded-circle" 
                                                     width="50" height="50" 
                                                     alt="{{ $testimonial->name }}">
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $testimonial->name }}</div>
                                                @if($testimonial->location)
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt"></i> {{ $testimonial->location }}
                                                    </small>
                                                @endif
                                                @if($testimonial->email)
                                                    <div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope"></i> {{ $testimonial->email }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    @foreach($testimonial->stars as $star)
                                                        <i class="{{ $star }}"></i>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted">{{ $testimonial->rating_text }}</small>
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    {{ $testimonial->short_message }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @if($testimonial->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                    
                                                    @if($testimonial->is_featured)
                                                        <span class="badge bg-warning">Unggulan</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $testimonial->display_order }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical btn-group-sm" role="group">
                                                    <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                                       class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                                                       class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                            onclick="toggleActive({{ $testimonial->id }})">
                                                        <i class="fas fa-{{ $testimonial->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                                            onclick="toggleFeatured({{ $testimonial->id }})">
                                                        <i class="fas fa-{{ $testimonial->is_featured ? 'star-half-alt' : 'star' }}"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteTestimonial({{ $testimonial->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $testimonials->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada testimoni</h5>
                            <p class="text-muted">Mulai tambahkan testimoni pelanggan untuk ditampilkan di homepage.</p>
                            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Testimoni Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forms for AJAX actions -->
<form id="toggleActiveForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="toggleFeaturedForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function toggleActive(id) {
    if (confirm('Apakah Anda yakin ingin mengubah status aktif testimoni ini?')) {
        const form = document.getElementById('toggleActiveForm');
        form.action = `/admin/testimonials/${id}/toggle-active`;
        form.submit();
    }
}

function toggleFeatured(id) {
    if (confirm('Apakah Anda yakin ingin mengubah status unggulan testimoni ini?')) {
        const form = document.getElementById('toggleFeaturedForm');
        form.action = `/admin/testimonials/${id}/toggle-featured`;
        form.submit();
    }
}

function deleteTestimonial(id) {
    if (confirm('Apakah Anda yakin ingin menghapus testimoni ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/testimonials/${id}`;
        form.submit();
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endpush