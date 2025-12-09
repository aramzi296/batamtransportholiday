<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Basic Information -->
            <div class="col-lg-6">
                <h6 class="fw-bold mb-3">Informasi Dasar</h6>
                
                <div class="mb-3">
                    <label class="form-label">Kategori *</label>
                    <select wire:model="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
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
                    <textarea wire:model="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                              placeholder="Deskripsi lengkap kendaraan..." required></textarea>
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
                        <select wire:model="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                            <option value="">Pilih Merek</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model *</label>
                        <input type="text" wire:model="model" class="form-control @error('model') is-invalid @enderror" required>
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun *</label>
                        <input type="number" wire:model="year" class="form-control @error('year') is-invalid @enderror" 
                               min="1990" max="{{ date('Y') + 1 }}" required>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Warna *</label>
                        <input type="text" wire:model="color" class="form-control @error('color') is-invalid @enderror" required>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bahan Bakar *</label>
                        <select wire:model="fuel_type" class="form-select @error('fuel_type') is-invalid @enderror" required>
                            <option value="">Pilih Bahan Bakar</option>
                            <option value="Bensin">Bensin</option>
                            <option value="Solar">Solar</option>
                            <option value="Listrik">Listrik</option>
                        </select>
                        @error('fuel_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transmisi *</label>
                        <select wire:model="transmission" class="form-select @error('transmission') is-invalid @enderror" required>
                            <option value="">Pilih Transmisi</option>
                            <option value="Manual">Manual</option>
                            <option value="Automatic">Automatic</option>
                            <option value="CVT">CVT</option>
                        </select>
                        @error('transmission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jumlah Kursi *</label>
                        <input type="number" wire:model="seats" class="form-control @error('seats') is-invalid @enderror" 
                               min="1" max="60" required>
                        @error('seats')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Plat Nomor *</label>
                        <input type="text" wire:model="plate_number" class="form-control @error('plate_number') is-invalid @enderror" 
                               placeholder="B 1234 ABC" required>
                        @error('plate_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Antrian</label>
                    <input type="number" wire:model="queue_number" class="form-control @error('queue_number') is-invalid @enderror" 
                           min="1" placeholder="Nomor antrian untuk urutan tampil">
                    @error('queue_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Nomor antrian menentukan urutan tampil kendaraan di halaman depan.</div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Rental Categories with Driver Options -->
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="fas fa-tags"></i> Kategori Harga Sewa</h6>
                    <button type="button" wire:click="addRentalCategory" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-plus"></i> Tambah Kategori
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kategori Sewa</th>
                                <th>Dengan Sopir</th>
                                <th>Harga (Rp)</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentalCategories as $index => $rc)
                            <tr>
                                <td>
                                    <select wire:model="rentalCategories.{{ $index }}.rental_category_id" 
                                            class="form-select form-select-sm">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($rentalCats as $rentalCat)
                                            <option value="{{ $rentalCat->id }}">
                                                {{ $rentalCat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               wire:model="rentalCategories.{{ $index }}.with_driver">
                                    </div>
                                </td>
                                <td>
                                    <input type="number" 
                                           wire:model="rentalCategories.{{ $index }}.price" 
                                           class="form-control form-control-sm" 
                                           placeholder="0" min="0" step="1000">
                                </td>
                                <td>
                                    <button type="button" wire:click="removeRentalCategory({{ $index }})" 
                                            class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <hr>

        <!-- Images Upload -->
        <div class="row">
            <div class="col-lg-12">
                <h6 class="fw-bold mb-3"><i class="fas fa-images"></i> Foto Kendaraan</h6>
                
                <div class="mb-3">
                    <label class="form-label">Upload Foto @if(!$vehicleId)(Bisa lebih dari satu) *@endif</label>
                    <input type="file" wire:model="images" class="form-control @error('images.*') is-invalid @enderror" 
                           accept="image/*" multiple @if(!$vehicleId) required @endif>
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB per foto. @if(!$vehicleId)Minimal 1 foto.@endif</div>
                </div>

                @if(!empty($existingImages))
                <div class="mb-3">
                    <label class="form-label">Foto yang Sudah Ada</label>
                    <div class="row">
                        @foreach($existingImages as $index => $img)
                        <div class="col-md-3 mb-2">
                            <div class="position-relative border rounded p-2" 
                                 style="background: {{ in_array($img['id'], $deleteImages ?? []) ? '#ffebee' : 'transparent' }}">
                                <img src="{{ $img['url'] }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                
                                <!-- Featured Badge -->
                                @if($img['is_featured'] && !in_array($img['id'], $deleteImages ?? []))
                                <span class="badge bg-success position-absolute top-0 start-0 m-2">Fitur</span>
                                @endif
                                
                                <!-- Delete Checkbox -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               wire:model="deleteImages" 
                                               value="{{ $img['id'] }}" 
                                               id="delete_img_{{ $img['id'] }}">
                                        <label class="form-check-label text-white bg-danger px-2 py-1 rounded" 
                                               for="delete_img_{{ $img['id'] }}"
                                               style="cursor: pointer;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Featured Radio -->
                                @if(!in_array($img['id'], $deleteImages ?? []))
                                <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-dark bg-opacity-75">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               wire:model="featured_image_index" 
                                               value="{{ $index }}" 
                                               id="featured_existing_{{ $img['id'] }}">
                                        <label class="form-check-label text-white" for="featured_existing_{{ $img['id'] }}" style="cursor: pointer;">
                                            <i class="fas fa-star"></i> Jadikan Fitur
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($images)
                <div class="mb-3">
                    <label class="form-label">Foto Baru</label>
                    <div class="row">
                        @foreach($images as $index => $image)
                        <div class="col-md-3 mb-2">
                            <div class="position-relative">
                                @if($image->temporaryUrl())
                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                @endif
                                <div class="form-check position-absolute top-0 start-0 m-2">
                                    <input class="form-check-input" type="radio" name="featured_image" 
                                           wire:model="featured_image_index" value="{{ count($existingImages) + $index }}" id="featured_new_{{ $index }}">
                                    <label class="form-check-label text-white bg-dark px-2 rounded" for="featured_new_{{ $index }}">
                                        Fitur
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" wire:model="is_available" id="is_available">
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
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>
                    <i class="fas fa-save"></i> {{ $vehicleId ? 'Update' : 'Simpan' }} Kendaraan
                </span>
                <span wire:loading>
                    <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
