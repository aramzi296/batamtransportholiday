<?php

namespace App\Livewire\Admin;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use App\Models\RentalCategory;
use App\Models\VehicleRentalCategory;
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
    public $category_id = '';
    public $description = '';
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
    
    // Rental categories with driver options
    public $rentalCategories = [];
    
    // Images
    public $images = [];
    public $featured_image_index = 0;
    public $imagePreviews = [];
    public $existingImages = [];
    public $deleteImages = [];

    protected function rules()
    {
        $rules = [
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'queue_number' => 'nullable|integer|min:1',
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

    public function mount($vehicleId = null)
    {
        $this->year = (int) date('Y');
        
        if ($vehicleId) {
            $vehicle = Vehicle::with(['rentalCategories', 'vehicleImages'])->findOrFail($vehicleId);
            $this->vehicleId = $vehicle->id;
            $this->category_id = $vehicle->category_id;
            $this->description = $vehicle->description;
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
            
            // Load existing rental categories
            $this->rentalCategories = [];
            foreach ($vehicle->rentalCategories as $vrc) {
                $this->rentalCategories[] = [
                    'rental_category_id' => $vrc->rental_category_id,
                    'with_driver' => $vrc->with_driver,
                    'price' => $vrc->price,
                ];
            }
            
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
        
        // Initialize rental categories if empty
        if (empty($this->rentalCategories)) {
            $rentalCats = RentalCategory::active()->orderBy('sort_order')->get();
            foreach ($rentalCats as $rc) {
                $this->rentalCategories[] = [
                    'rental_category_id' => $rc->id,
                    'with_driver' => false,
                    'price' => null,
                ];
            }
        }
    }

    public function updatedImages()
    {
        $this->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function addRentalCategory()
    {
        $this->rentalCategories[] = [
            'rental_category_id' => '',
            'with_driver' => false,
            'price' => null,
        ];
    }

    public function removeRentalCategory($index)
    {
        unset($this->rentalCategories[$index]);
        $this->rentalCategories = array_values($this->rentalCategories);
    }

    public function save()
    {
        $this->validate();

        $brand = VehicleBrand::find($this->brand_id);
        $vehicleName = ($brand ? $brand->name : '') . ' ' . $this->model;
        $vehicleName = trim($vehicleName);

        if ($this->vehicleId) {
            // Update existing vehicle
            $vehicle = Vehicle::findOrFail($this->vehicleId);
            $vehicle->update([
                'category_id' => $this->category_id,
                'description' => $this->description,
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
            ]);
        } else {
            // Create new vehicle
            $vehicle = Vehicle::create([
                'name' => $vehicleName,
                'slug' => Str::slug($vehicleName . '-' . uniqid()),
                'category_id' => $this->category_id,
                'description' => $this->description,
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
            ]);
        }

        // Handle rental categories
        VehicleRentalCategory::where('vehicle_id', $vehicle->id)->delete();
        foreach ($this->rentalCategories as $rc) {
            if (!empty($rc['rental_category_id']) && isset($rc['price']) && $rc['price'] > 0) {
                VehicleRentalCategory::create([
                    'vehicle_id' => $vehicle->id,
                    'rental_category_id' => $rc['rental_category_id'],
                    'with_driver' => $rc['with_driver'] ?? false,
                    'price' => $rc['price'],
                ]);
            }
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

        session()->flash('success', $this->vehicleId ? 'Kendaraan berhasil diperbarui!' : 'Kendaraan berhasil ditambahkan!');
        return redirect()->route('admin.vehicles.index');
    }

    public function render()
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        $rentalCats = RentalCategory::active()->orderBy('sort_order')->get();
        
        return view('livewire.admin.vehicle-form', [
            'categories' => $categories,
            'brands' => $brands,
            'rentalCats' => $rentalCats,
        ]);
    }
}
