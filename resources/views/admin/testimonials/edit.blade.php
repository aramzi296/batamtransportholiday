@extends('layouts.admin')

@section('title', 'Edit Testimoni')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="h3 mb-0">Edit Testimoni</h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Mohon periksa kembali data yang Anda masukkan.</strong>
                    <div class="mt-2">{{ $errors->first() }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Informasi Testimoni</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $testimonial->name) }}" 
                                       placeholder="Masukkan nama pelanggan" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $testimonial->email) }}" 
                                       placeholder="email@contoh.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="location" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                       id="location" name="location" value="{{ old('location', $testimonial->location) }}" 
                                       placeholder="Kota, Provinsi">
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <select class="form-select @error('rating') is-invalid @enderror" id="rating" name="rating" required>
                                    <option value="">Pilih Rating</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>
                                            {{ $i }} Bintang - 
                                            @switch($i)
                                                @case(5) Sangat Baik @break
                                                @case(4) Baik @break
                                                @case(3) Cukup @break
                                                @case(2) Buruk @break
                                                @case(1) Sangat Buruk @break
                                            @endswitch
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">Pesan Testimoni <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="5" 
                                          placeholder="Tulis testimoni pelanggan di sini..." required>{{ old('message', $testimonial->message) }}</textarea>
                                <div class="form-text">Maksimal 1000 karakter. <span id="charCount">0</span>/1000</div>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="photo" class="form-label">Foto Pelanggan</label>
                                
                                @if($testimonial->photo)
                                    <div class="mb-2">
                                        <img src="{{ $testimonial->photo_url }}" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                        <div class="small text-muted">Foto saat ini</div>
                                    </div>
                                @endif

                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="photoPreview" class="mt-2"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="display_order" class="form-label">Urutan Tampil</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                                       id="display_order" name="display_order" value="{{ old('display_order', $testimonial->display_order) }}" 
                                       min="0" placeholder="0">
                                <div class="form-text">Angka kecil akan ditampilkan lebih dulu. Default: 0</div>
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <h6>Pengaturan Status</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Aktif</strong>
                                        <br><small class="text-muted">Testimoni akan ditampilkan di website</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <strong>Unggulan</strong>
                                        <br><small class="text-muted">Testimoni akan diprioritaskan untuk ditampilkan</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Testimoni
                                </button>
                                <a href="{{ route('admin.testimonials.show', $testimonial) }}" class="btn btn-outline-info ms-2">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi Testimoni</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Dibuat pada:</label>
                        <div>{{ $testimonial->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">Terakhir diupdate:</label>
                        <div>{{ $testimonial->updated_at->format('d F Y, H:i') }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Status saat ini:</label>
                        <div>
                            @if($testimonial->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                            
                            @if($testimonial->is_featured)
                                <span class="badge bg-warning ms-1">Unggulan</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb"></i> Tips Edit Testimoni:</h6>
                        <ul class="mb-0 small">
                            <li>Pastikan perubahan masih mencerminkan testimoni asli</li>
                            <li>Update foto hanya jika diperlukan</li>
                            <li>Ubah status unggulan untuk prioritas tampil</li>
                            <li>Atur urutan tampil untuk kontrol posisi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Character counter for message
document.getElementById('message').addEventListener('input', function() {
    const maxLength = 1000;
    const currentLength = this.value.length;
    document.getElementById('charCount').textContent = currentLength;
    
    // Change color based on usage
    const counter = document.getElementById('charCount').parentElement;
    if (currentLength > maxLength * 0.9) {
        counter.className = 'form-text text-warning';
    } else if (currentLength >= maxLength) {
        counter.className = 'form-text text-danger';
    } else {
        counter.className = 'form-text text-muted';
    }
});

// Photo preview
document.getElementById('photo').addEventListener('change', function(e) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="mt-2">
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                    <div class="small text-muted mt-1">Preview foto baru</div>
                </div>
            `;
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});

// Initialize character counter
document.addEventListener('DOMContentLoaded', function() {
    const messageField = document.getElementById('message');
    if (messageField.value) {
        messageField.dispatchEvent(new Event('input'));
    }
});
</script>
@endpush