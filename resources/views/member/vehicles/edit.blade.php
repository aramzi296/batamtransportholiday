@extends('layouts.member')

@section('title', 'Edit Kendaraan')
@section('page-title', 'Edit Kendaraan')

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
    .delete-image-btn {
        position: absolute;
        top: 5px;
        left: 5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        cursor: pointer;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Form Edit Kendaraan</h5>
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

        <form action="{{ route('member.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle"></i> Informasi Dasar</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kendaraan *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $vehicle->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $vehicle->category_id) == $category->id ? 'selected' : '' }}>
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
                                  placeholder="Deskripsi lengkap kendaraan..." required>{{ old('description', $vehicle->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Per Hari (Rp) *</label>
                        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" 
                               value="{{ old('price_per_day', $vehicle->price_per_day) }}" min="0" step="1000" required>
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
                                   value="{{ old('brand', $vehicle->brand) }}" required>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model *</label>
                            <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                                   value="{{ old('model', $vehicle->model) }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun *</label>
                            <input type="number" name="year" class="form-control @error('year') is-invalid @enderror" 
                                   value="{{ old('year', $vehicle->year) }}" min="1990" max="{{ date('Y') + 1 }}" required>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna *</label>
                            <input type="text" name="color" class="form-control @error('color') is-invalid @enderror" 
                                   value="{{ old('color', $vehicle->color) }}" required>
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
                                <option value="Bensin" {{ old('fuel_type', $vehicle->fuel_type) == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                                <option value="Solar" {{ old('fuel_type', $vehicle->fuel_type) == 'Solar' ? 'selected' : '' }}>Solar</option>
                                <option value="Listrik" {{ old('fuel_type', $vehicle->fuel_type) == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                            </select>
                            @error('fuel_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transmisi *</label>
                            <select name="transmission" class="form-select @error('transmission') is-invalid @enderror" required>
                                <option value="">Pilih Transmisi</option>
                                <option value="Manual" {{ old('transmission', $vehicle->transmission) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                <option value="Automatic" {{ old('transmission', $vehicle->transmission) == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                <option value="CVT" {{ old('transmission', $vehicle->transmission) == 'CVT' ? 'selected' : '' }}>CVT</option>
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
                                   value="{{ old('seats', $vehicle->seats) }}" min="1" max="60" required>
                            @error('seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plat Nomor *</label>
                            <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror" 
                                   value="{{ old('plate_number', $vehicle->plate_number) }}" placeholder="B 1234 ABC" required>
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
                                $vehicleFeatures = old('features', $vehicle->features ?? []);
                            @endphp
                            @foreach($commonFeatures as $feature)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="features[]" 
                                               value="{{ $feature }}" id="feature_{{ $loop->index }}"
                                               {{ in_array($feature, $vehicleFeatures) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_{{ $loop->index }}">
                                            {{ $feature }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Existing Images -->
            <div class="row">
                <div class="col-lg-12">
                    <h6 class="fw-bold mb-3"><i class="fas fa-images"></i> Foto Kendaraan yang Ada</h6>
                    
                    @if($vehicle->vehicleImages->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Pilih Foto untuk Dihapus (opsional)</label>
                            <div id="existing-images-container" class="d-flex flex-wrap">
                                @foreach($vehicle->vehicleImages as $image)
                                    <div class="image-preview-container" id="existing-image-{{ $image->id }}">
                                        <img src="{{ $image->image_url }}" class="image-preview" alt="Foto {{ $loop->index + 1 }}">
                                        @if($image->is_featured)
                                            <div class="featured-badge">Fitur</div>
                                        @endif
                                        <button type="button" class="delete-image-btn" onclick="markForDeletion({{ $image->id }})" title="Hapus">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="form-check mt-2 text-center">
                                            <input class="form-check-input" type="radio" name="featured_image" 
                                                   value="{{ $image->id }}" id="featured_existing_{{ $image->id }}"
                                                   {{ $image->is_featured ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="featured_existing_{{ $image->id }}">
                                                Jadikan Fitur
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="delete-images-input"></div>
                    @else
                        <p class="text-muted">Belum ada foto kendaraan.</p>
                    @endif
                </div>
            </div>

            <hr>

            <!-- New Images Upload -->
            <div class="row">
                <div class="col-lg-12">
                    <h6 class="fw-bold mb-3"><i class="fas fa-upload"></i> Tambah Foto Baru (Opsional)</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Foto Baru</label>
                        <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                               accept="image/*" multiple onchange="previewNewImages(this)">
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per foto.</div>
                    </div>

                    <div id="new-image-preview-container" class="mb-3"></div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_available" id="is_available" 
                           {{ old('is_available', $vehicle->is_available) ? 'checked' : '' }}>
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
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let deletedImageIds = [];
    let newImageFiles = [];
    let newImagePreviews = [];

    function markForDeletion(imageId) {
        if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
            deletedImageIds.push(imageId);
            document.getElementById('existing-image-' + imageId).style.opacity = '0.5';
            document.getElementById('existing-image-' + imageId).style.border = '3px solid red';
            
            // Add hidden input for deletion
            const container = document.getElementById('delete-images-input');
            if (!document.getElementById('delete-' + imageId)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = imageId;
                input.id = 'delete-' + imageId;
                container.appendChild(input);
            }
        }
    }

    function previewNewImages(input) {
        const container = document.getElementById('new-image-preview-container');
        container.innerHTML = '';

        if (input.files && input.files.length > 0) {
            newImageFiles = Array.from(input.files);
            
            newImageFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'image-preview-container';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="image-preview" alt="Preview Baru ${index + 1}">
                    `;
                    container.appendChild(previewDiv);
                    newImagePreviews.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endpush
@endsection






