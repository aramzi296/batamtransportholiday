@extends('layouts.admin')

@section('title', 'Tambah Kendaraan')
@section('page-title', 'Tambah Kendaraan Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Form Tambah Kendaraan</h5>
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

        <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">Informasi Dasar</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required onchange="updateCategoryPrice()">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        data-price="{{ $category->price ?? 0 }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="category-price-info" class="form-text mt-2" style="display: none;">
                            <i class="fas fa-info-circle text-primary"></i> 
                            Harga rental per hari: <strong class="text-success" id="category-price-value">Rp 0</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi *</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Deskripsi lengkap kendaraan..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Specifications -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">Spesifikasi</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merek *</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="">Pilih Merek</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <a href="{{ route('admin.brands.create') }}" target="_blank">
                                    <i class="fas fa-plus"></i> Tambah Merek Baru
                                </a>
                            </div>
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

                    <div class="mb-3">
                        <label class="form-label">Nomor Antrian</label>
                        <input type="number" name="queue_number" class="form-control @error('queue_number') is-invalid @enderror" 
                               value="{{ old('queue_number') }}" min="1" placeholder="Nomor antrian untuk urutan tampil">
                        @error('queue_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nomor antrian menentukan urutan tampil kendaraan di halaman depan. Kosongkan jika belum ditentukan.</div>
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
                <a href="{{ route('admin.vehicles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
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
                    previewDiv.className = 'd-inline-block me-2 mb-2 position-relative';
                    previewDiv.style.cssText = 'width: 150px; height: 150px; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;" alt="Preview ${index + 1}">
                        <div class="position-absolute top-0 end-0 bg-success text-white px-2 py-1" id="badge-${index}" style="display: ${index === 0 ? 'block' : 'none'}; font-size: 12px;">Fitur</div>
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

    function updateCategoryPrice() {
        const categorySelect = document.getElementById('category_id');
        const priceInfo = document.getElementById('category-price-info');
        const priceValue = document.getElementById('category-price-value');
        
        if (categorySelect.value) {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            if (price > 0) {
                priceValue.textContent = 'Rp ' + price.toLocaleString('id-ID');
                priceInfo.style.display = 'block';
            } else {
                priceInfo.style.display = 'none';
            }
        } else {
            priceInfo.style.display = 'none';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCategoryPrice();
    });
</script>
@endpush
@endsection