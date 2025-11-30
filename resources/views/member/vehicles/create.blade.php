@extends('layouts.member')

@section('title', 'Tambah Kendaraan')
@section('page-title', 'Tambah Kendaraan Baru')

@push('styles')
<style>
    .image-preview {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin: 5px;
    }
    .image-preview-container {
        position: relative;
        display: inline-block;
        margin: 5px;
    }
    .featured-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-car"></i> Form Tambah Kendaraan</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('member.vehicles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Informasi Dasar</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kendaraan *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi *</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Deskripsi lengkap kendaraan..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Per Hari (Rp) *</label>
                        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" 
                               value="{{ old('price_per_day') }}" min="0" step="1000" required>
                        @error('price_per_day')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specifications -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3"><i class="fas fa-cog"></i> Spesifikasi</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merk *</label>
                            <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" 
                                   value="{{ old('brand') }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model *</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                                   value="{{ old('model') }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun *</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" 
                                   value="{{ old('year', date('Y')) }}" min="1990" max="{{ date('Y') + 1 }}" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna *</label>
                            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" 
                                   value="{{ old('color') }}" required>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bahan Bakar *</label>
                            <select name="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror" required>
                                <option value="">Pilih Bahan Bakar</option>
                                <option value="Bensin" {{ old('fuel_type') == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                                <option value="Solar" {{ old('fuel_type') == 'Solar' ? 'selected' : '' }}>Solar</option>
                                <option value="Listrik" {{ old('fuel_type') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                            </select>
                            @error('fuel_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transmisi *</label>
                            <select name="transmission" class="form-select @error('transmission') is-invalid @enderror" required>
                                <option value="">Pilih Transmisi</option>
                                <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="CVT" {{ old('transmission') == 'CVT' ? 'selected' : '' }}>CVT</option>
                            </select>
                            @error('transmission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Kursi *</label>
                            <input type="number" name="seats" class="form-control @error('seats') is-invalid @enderror" 
                                   value="{{ old('seats') }}" min="1" max="60" required>
                            @error('seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor *</label>
                            <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror" 
                                   value="{{ old('plate_number') }}" placeholder="B 1234 ABC" required>
                            @error('plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Features -->
            <div class="row">
                <div class="col-lg-12">
                    <h6 class="fw-bold mb-3"><i class="fas fa-star"></i> Fitur & Fasilitas</h6>
                    <div class="mb-3">
                        <label class="form-label">Fitur</label>
                        <div class="row">
                            @php
                                $commonFeatures = ['AC', 'GPS', 'Audio System', 'USB Port', 'Bluetooth', 'Kamera Mundur', 'Parkir Otomatis', 'Sunroof', 'Leather Seats', 'Third Row Seats'];
                            @endphp
                            @foreach($commonFeatures as $feature)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" 
                                               value="{{ $feature }}" id="feature_{{ $loop->index }}"
                                               {{ in_array($feature, old('features', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_{{ $loop->index }}">
                                            {{ $feature }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <input type="text" class="form-control" placeholder="Fitur lain (pisahkan dengan koma)" 
                                   onchange="addCustomFeatures(this.value)">
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Images Upload -->
            <div class="row">
                <div class="col-lg-12">
                    <h6 class="fw-bold mb-3"><i class="fas fa-images"></i> Foto Kendaraan</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Foto (Bisa lebih dari satu) *</label>
                        <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                               accept="image/*" multiple required onchange="previewImages(this)">
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per foto. Minimal 1 foto.</div>
                    </div>

                    <div id="image-preview-container" class="mb-3"></div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Foto Fitur (Salah satu foto akan ditampilkan sebagai foto utama)</label>
                        <div id="featured-image-selector"></div>
                        <input type="hidden" name="featured_image" id="featured_image" value="0">
                        <div class="form-text">Pilih salah satu foto sebagai foto fitur/utama kendaraan</div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                           {{ old('is_available', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_available">
                        Kendaraan tersedia untuk disewa
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('member.vehicles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-save"></i> Simpan Kendaraan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let imageFiles = [];
    let imagePreviews = [];

    function previewImages(input) {
        const container = document.getElementById('image-preview-container');
        const selector = document.getElementById('featured-image-selector');
        container.innerHTML = '';
        selector.innerHTML = '';
        imagePreviews = [];

        if (input.files && input.files.length > 0) {
            imageFiles = Array.from(input.files);
            
            imageFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Preview
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview-container';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="image-preview" alt="Preview ${index + 1}">
                        <div class="featured-badge" id="badge-${index}" style="display: none;">Fitur</div>
                    `;
                    container.appendChild(previewDiv);
                    imagePreviews.push(e.target.result);

                    // Radio button for featured
                    const radioDiv = document.createElement('div');
                    radioDiv.className = 'form-check mb-2';
                    radioDiv.innerHTML = `
                        <input class="form-check-input" type="radio" name="featured_image_radio" 
                               value="${index}" id="featured_${index}" ${index === 0 ? 'checked' : ''} 
                               onchange="setFeaturedImage(${index})">
                        <label class="form-check-label" for="featured_${index}">
                            Foto ${index + 1} - ${file.name}
                        </label>
                    `;
                    selector.appendChild(radioDiv);
                };
                reader.readAsDataURL(file);
            });

            // Set first image as featured by default
            if (imageFiles.length > 0) {
                document.getElementById('featured_image').value = '0';
                document.getElementById('badge-0').style.display = 'block';
            }
        }
    }

    function setFeaturedImage(index) {
        document.getElementById('featured_image').value = index;
        // Hide all badges
        for (let i = 0; i < imagePreviews.length; i++) {
            const badge = document.getElementById(`badge-${i}`);
            if (badge) badge.style.display = 'none';
        }
        // Show selected badge
        const selectedBadge = document.getElementById(`badge-${index}`);
        if (selectedBadge) selectedBadge.style.display = 'block';
    }

    function addCustomFeatures(value) {
        if (value.trim()) {
            const features = value.split(',').map(f => f.trim()).filter(f => f);
            features.forEach(feature => {
                // Add to features array if not exists
                const checkbox = document.querySelector(`input[value="${feature}"]`);
                if (!checkbox) {
                    // Could add dynamically, but for simplicity, just show in textarea
                    console.log('Custom feature:', feature);
                }
            });
        }
    }
</script>
@endpush
@endsection




