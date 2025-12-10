<?php

namespace App\Livewire\Admin;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use App\Models\User;
use App\Models\VehicleImage;
use App\Helpers\ImageHelper;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VehicleForm extends Component
{
    use WithFileUploads;

    public $vehicleId = null;
    public $name = '';
    public $category_id = '';
    public $description = '';
    public $price_per_day = '';
    public $price_per_day_no_driver = '';
    public $machine_cc = '';
    public $member_id = '';
    public $brand_id = '';
    public $model = '';
    public $year;
    public $color = '';
    public $fuel_type = '';
    public $transmission = '';
    public $seats = '';
    public $plate_number = '';
    public $queue_number = '';
    public $is_available = true;
    public $features = [];
    public $customFeatures = '';
    
    // Images
    public $images = [];
    public $featured_image_index = 0;
    public $imagePreviews = [];
    public $existingImages = [];
    public $deleteImages = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_day_no_driver' => 'nullable|numeric|min:0',
            'machine_cc' => 'nullable|integer|min:1',
            'member_id' => 'nullable|exists:users,id',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'queue_number' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string|max:100',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        
        // Plate number validation - exclude current vehicle if updating
        if ($this->vehicleId) {
            $rules['plate_number'] = 'required|string|unique:vehicles,plate_number,' . $this->vehicleId;
            $rules['images'] = 'nullable|array';
        } else {
            $rules['plate_number'] = 'required|string|unique:vehicles,plate_number';
            $rules['images'] = 'required|array|min:1';
        }
        
        return $rules;
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama kendaraan wajib diisi.',
            'name.string' => 'Nama kendaraan harus berupa teks.',
            'name.max' => 'Nama kendaraan maksimal 255 karakter.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'price_per_day.required' => 'Harga per hari wajib diisi.',
            'price_per_day.numeric' => 'Harga per hari harus berupa angka.',
            'price_per_day.min' => 'Harga per hari minimal 0.',
            'price_per_day_no_driver.numeric' => 'Harga per hari tanpa sopir harus berupa angka.',
            'price_per_day_no_driver.min' => 'Harga per hari tanpa sopir minimal 0.',
            'machine_cc.integer' => 'Kapasitas mesin (CC) harus berupa angka bulat.',
            'machine_cc.min' => 'Kapasitas mesin (CC) minimal 1.',
            'member_id.exists' => 'Pemilik yang dipilih tidak valid.',
            'brand_id.required' => 'Merek wajib dipilih.',
            'brand_id.exists' => 'Merek yang dipilih tidak valid.',
            'model.required' => 'Model wajib diisi.',
            'model.string' => 'Model harus berupa teks.',
            'model.max' => 'Model maksimal 100 karakter.',
            'year.required' => 'Tahun wajib diisi.',
            'year.integer' => 'Tahun harus berupa angka.',
            'year.min' => 'Tahun minimal 1990.',
            'year.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 1) . '.',
            'color.required' => 'Warna wajib diisi.',
            'color.string' => 'Warna harus berupa teks.',
            'color.max' => 'Warna maksimal 50 karakter.',
            'fuel_type.required' => 'Bahan bakar wajib dipilih.',
            'fuel_type.string' => 'Bahan bakar harus berupa teks.',
            'transmission.required' => 'Transmisi wajib dipilih.',
            'transmission.string' => 'Transmisi harus berupa teks.',
            'seats.required' => 'Jumlah kursi wajib diisi.',
            'seats.integer' => 'Jumlah kursi harus berupa angka.',
            'seats.min' => 'Jumlah kursi minimal 1.',
            'seats.max' => 'Jumlah kursi maksimal 60.',
            'plate_number.required' => 'Plat nomor wajib diisi.',
            'plate_number.string' => 'Plat nomor harus berupa teks.',
            'plate_number.unique' => 'Plat nomor sudah digunakan.',
            'queue_number.integer' => 'Nomor antrian harus berupa angka.',
            'queue_number.min' => 'Nomor antrian minimal 1.',
            'images.required' => 'Foto kendaraan wajib diupload.',
            'images.min' => 'Minimal 1 foto kendaraan harus diupload.',
            'images.*.image' => 'File yang diupload harus berupa gambar.',
            'images.*.mimes' => 'Format gambar yang diizinkan: JPEG, PNG, JPG, GIF.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function mount($vehicleId = null)
    {
        $this->year = (int) date('Y');
        
        if ($vehicleId) {
            $vehicle = Vehicle::with(['vehicleImages'])->findOrFail($vehicleId);
            $this->vehicleId = $vehicle->id;
            $this->name = $vehicle->name;
            $this->category_id = $vehicle->category_id;
            $this->description = $vehicle->description;
            $this->price_per_day = $vehicle->price_per_day;
            $this->price_per_day_no_driver = $vehicle->price_per_day_no_driver;
            $this->machine_cc = $vehicle->machine_cc;
            $this->member_id = $vehicle->member_id;
            $this->brand_id = $vehicle->brand_id;
            $this->model = $vehicle->model;
            $this->year = $vehicle->year;
            $this->color = $vehicle->color;
            $this->fuel_type = $vehicle->fuel_type;
            $this->transmission = $vehicle->transmission;
            $this->seats = $vehicle->seats;
            $this->plate_number = $vehicle->plate_number;
            $this->queue_number = $vehicle->queue_number;
            $this->is_available = $vehicle->is_available;
            $this->features = $vehicle->features ?? [];
            
            // Load existing images
            $this->existingImages = $vehicle->vehicleImages->map(function($img) {
                return [
                    'id' => $img->id,
                    'url' => $img->image_url,
                    'is_featured' => $img->is_featured,
                ];
            })->toArray();
            
            // Set featured image index
            $featuredIndex = $vehicle->vehicleImages->search(function($img) {
                return $img->is_featured;
            });
            if ($featuredIndex !== false) {
                $this->featured_image_index = $featuredIndex;
            }
        }
    }

    public function updatedImages()
    {
        $this->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function addCustomFeatures()
    {
        if (!empty($this->customFeatures)) {
            $newFeatures = array_map('trim', explode(',', $this->customFeatures));
            foreach ($newFeatures as $feature) {
                $feature = trim($feature);
                if (!empty($feature) && !in_array($feature, $this->features)) {
                    $this->features[] = $feature;
                }
            }
            $this->customFeatures = '';
        }
    }


    public function save()
    {
        try {
            $this->validate();

            $brand = VehicleBrand::find($this->brand_id);
            $vehicleName = $this->name ?: (($brand ? $brand->name : '') . ' ' . $this->model);
            $vehicleName = trim($vehicleName);

            $data = [
                'name' => $vehicleName,
                'category_id' => $this->category_id,
                'description' => $this->description,
                'price_per_day' => $this->price_per_day,
                'price_per_day_no_driver' => $this->price_per_day_no_driver ?: null,
                'machine_cc' => $this->machine_cc ?: null,
                'member_id' => $this->member_id ?: null,
                'brand_id' => $this->brand_id,
                'brand' => $brand ? $brand->name : null,
                'model' => $this->model,
                'year' => $this->year,
                'color' => $this->color,
                'fuel_type' => $this->fuel_type,
                'transmission' => $this->transmission,
                'seats' => $this->seats,
                'plate_number' => $this->plate_number,
                'queue_number' => $this->queue_number ?: null,
                'is_available' => $this->is_available,
                'features' => $this->features ?? [],
            ];

            if ($this->vehicleId) {
                // Update existing vehicle
                $vehicle = Vehicle::findOrFail($this->vehicleId);
                $data['slug'] = Str::slug($vehicleName . '-' . $vehicle->id);
                $vehicle->update($data);
            } else {
                // Create new vehicle
                $data['slug'] = Str::slug($vehicleName . '-' . uniqid());
                $vehicle = Vehicle::create($data);
            }

            // Handle delete images FIRST (before upload)
            if (!empty($this->deleteImages)) {
                foreach ($this->deleteImages as $imageId) {
                    $vehicleImage = VehicleImage::find($imageId);
                    if ($vehicleImage) {
                        // Delete from storage
                        if (Storage::disk('public')->exists($vehicleImage->image_path)) {
                            Storage::disk('public')->delete($vehicleImage->image_path);
                        }
                        if ($vehicleImage->thumbnail_path && Storage::disk('public')->exists($vehicleImage->thumbnail_path)) {
                            Storage::disk('public')->delete($vehicleImage->thumbnail_path);
                        }
                        $vehicleImage->delete();
                    }
                }
            }

            // Handle image uploads
            if (!empty($this->images)) {
                $existingCount = $vehicle->vehicleImages()->count();
                $order = $existingCount;
                
                foreach ($this->images as $index => $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('vehicles', $filename, 'public');
                    
                    $thumbnailPath = ImageHelper::createThumbnail($path);
                    
                    VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'thumbnail_path' => $thumbnailPath,
                        'is_featured' => false, // Will be set below
                        'order' => $order++,
                    ]);
                }
            }

            // Handle featured image update (after delete and upload)
            // Reload all images after delete and upload
            $allImages = $vehicle->vehicleImages()->orderBy('order')->get();
            if ($allImages->count() > 0) {
                // Reset all featured
                $allImages->each(function($img) {
                    $img->update(['is_featured' => false]);
                });
                
                // Build ordered list: existing (not deleted) first, then new uploads
                $orderedImages = collect();
                
                // Add existing images (not deleted) in their original order
                foreach ($this->existingImages as $originalIndex => $existingImg) {
                    if (!in_array($existingImg['id'], $this->deleteImages ?? [])) {
                        $img = $allImages->firstWhere('id', $existingImg['id']);
                        if ($img) {
                            $orderedImages->push($img);
                        }
                    }
                }
                
                // Add new uploaded images
                $existingIds = collect($this->existingImages)->pluck('id')->toArray();
                $newImages = $allImages->filter(function($img) use ($existingIds) {
                    return !in_array($img->id, $existingIds);
                })->sortBy('id');
                $orderedImages = $orderedImages->merge($newImages);
                
                // Set new featured based on index
                $featuredIndex = (int)($this->featured_image_index ?? 0);
                if ($featuredIndex >= 0 && $featuredIndex < $orderedImages->count()) {
                    $orderedImages[$featuredIndex]->update(['is_featured' => true]);
                } else {
                    // Default to first if invalid index
                    $orderedImages->first()->update(['is_featured' => true]);
                }
            }

            $this->dispatch('swal', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => $this->vehicleId ? 'Kendaraan berhasil diperbarui!' : 'Kendaraan berhasil ditambahkan!'
            ]);

            // Redirect after a short delay to show the success message
            return redirect()->route('admin.vehicles.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Validasi Gagal!',
                'message' => 'Mohon periksa kembali data yang diinput.'
            ]);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'type' => 'error',
                'title' => 'Terjadi Kesalahan!',
                'message' => 'Gagal menyimpan data kendaraan. ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        $members = User::where('role', 'member')->orderBy('name')->get();
        
        return view('livewire.admin.vehicle-form', [
            'categories' => $categories,
            'brands' => $brands,
            'members' => $members,
        ]);
    }
}
