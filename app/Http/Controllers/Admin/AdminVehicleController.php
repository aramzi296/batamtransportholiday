<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminVehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['category', 'brand', 'vehicleImages'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        return view('admin.vehicles.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'queue_number' => 'nullable|integer|min:1',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_image' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get brand name for backward compatibility
        $brand = VehicleBrand::find($request->brand_id);
        
        // Get category to get price
        $category = VehicleCategory::find($request->category_id);
        
        // Generate name from brand + model
        $vehicleName = ($brand ? $brand->name : '') . ' ' . $request->model;
        $vehicleName = trim($vehicleName);
        
        // Get price from category, default to 0 if category price is null
        $pricePerDay = $category && $category->price ? $category->price : 0;
        
        $vehicle = Vehicle::create([
            'name' => $vehicleName,
            'slug' => Str::slug($vehicleName . '-' . uniqid()),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price_per_day' => $pricePerDay, // Get from category
            'brand_id' => $request->brand_id,
            'brand' => $brand ? $brand->name : null,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'plate_number' => $request->plate_number,
            'features' => [], // Default empty array
            'is_available' => $request->has('is_available'),
            'queue_number' => $request->queue_number,
        ]);

        // Handle image uploads - Upload ke S3 dengan fallback ke local
        if ($request->hasFile('images')) {
            $order = 0;
            $featuredIndex = (int)($request->featured_image ?? 0);
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = null;
                
                // Coba upload ke S3 terlebih dahulu
                try {
                    $path = $image->storeAs('vehicles', $filename, 's3');
                } catch (\Exception $e) {
                    // Jika S3 gagal, fallback ke local storage
                    try {
                        // Pastikan direktori ada
                        $dir = public_path('images/vehicles');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $image->move($dir, $filename);
                        $path = 'images/vehicles/' . $filename;
                    } catch (\Exception $e2) {
                        // Jika local juga gagal, skip gambar ini
                        \Log::error('Failed to upload vehicle image: ' . $e2->getMessage());
                        continue;
                    }
                }
                
                // Hanya simpan jika path berhasil
                if ($path) {
                    // Buat thumbnail
                    $thumbnailPath = \App\Helpers\ImageHelper::createThumbnail($path);
                    
                    \App\Models\VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'thumbnail_path' => $thumbnailPath,
                        'is_featured' => ($featuredIndex == $index),
                        'order' => $order++,
                    ]);
                }
            }
            
            // Jika tidak ada yang dipilih sebagai featured, set foto pertama
            if ($vehicle->vehicleImages()->where('is_featured', true)->count() == 0) {
                $firstImage = $vehicle->vehicleImages()->first();
                if ($firstImage) {
                    $firstImage->update(['is_featured' => true]);
                }
            }
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['category', 'brand']);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        $vehicle->load('vehicleImages'); // Load vehicleImages relationship
        return view('admin.vehicles.edit', compact('vehicle', 'categories', 'brands'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'price_per_day' => 'nullable|numeric|min:0',
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'queue_number' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_image' => 'nullable',
            'delete_images' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get brand name for backward compatibility
        $brand = VehicleBrand::find($request->brand_id);
        
        // Get category to get price
        $category = VehicleCategory::find($request->category_id);
        
        // Generate name from brand + model if name is not provided
        $vehicleName = $request->filled('name') ? $request->name : (($brand ? $brand->name : '') . ' ' . $request->model);
        $vehicleName = trim($vehicleName);
        
        // Get price from category if not manually set, default to 0 if category price is null
        $pricePerDay = $request->filled('price_per_day') 
            ? $request->price_per_day 
            : ($category && $category->price ? $category->price : ($vehicle->price_per_day ?? 0));
        
        $vehicle->update([
            'name' => $vehicleName,
            'slug' => Str::slug($vehicleName . '-' . $vehicle->id),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price_per_day' => $pricePerDay, // Get from category if not manually set
            'brand_id' => $request->brand_id,
            'brand' => $brand ? $brand->name : null,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'plate_number' => $request->plate_number,
            'features' => $request->features ?? [],
            'is_available' => $request->has('is_available'),
            'queue_number' => $request->queue_number,
        ]);

        // Handle delete images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = \App\Models\VehicleImage::find($imageId);
                if ($image && $image->vehicle_id == $vehicle->id) {
                    // Normalize path untuk S3
                    $s3Path = $image->image_path;
                    if (strpos($s3Path, 'images/vehicles/') === 0) {
                        $s3Path = str_replace('images/vehicles/', 'vehicles/', $s3Path);
                    }
                    
                    // Hapus file dari S3 (jika path adalah S3 path)
                    if (preg_match('/^(vehicles|profiles|testimonials)\//', $s3Path)) {
                        try {
                            if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($s3Path)) {
                                \Illuminate\Support\Facades\Storage::disk('s3')->delete($s3Path);
                            }
                        } catch (\Exception $e) {
                            // Ignore error jika S3 belum dikonfigurasi
                        }
                    }
                    
                    // Fallback: hapus dari local jika masih ada
                    $filePath = public_path($image->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }
        }

        // Handle featured image first - Reset all featured
        $vehicle->vehicleImages()->update(['is_featured' => false]);
        
        // Handle new image uploads - Upload ke S3
        $newImageIds = [];
        if ($request->hasFile('images')) {
            $maxOrder = $vehicle->vehicleImages()->max('order') ?? -1;
            $order = $maxOrder + 1;
            $featuredIndex = null;
            
            // Check if featured_image is for new images
            if ($request->has('featured_image') && strpos($request->featured_image, 'new_') === 0) {
                $featuredIndex = (int)str_replace('new_', '', $request->featured_image);
            }
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = null;
                
                // Coba upload ke S3 terlebih dahulu
                try {
                    $path = $image->storeAs('vehicles', $filename, 's3');
                } catch (\Exception $e) {
                    // Jika S3 gagal, fallback ke local storage
                    try {
                        // Pastikan direktori ada
                        $dir = public_path('images/vehicles');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $image->move($dir, $filename);
                        $path = 'images/vehicles/' . $filename;
                    } catch (\Exception $e2) {
                        // Jika local juga gagal, skip gambar ini
                        \Log::error('Failed to upload vehicle image: ' . $e2->getMessage());
                        continue;
                    }
                }
                
                // Hanya simpan jika path berhasil
                if ($path) {
                    // Buat thumbnail
                    $thumbnailPath = \App\Helpers\ImageHelper::createThumbnail($path);
                    
                    $vehicleImage = \App\Models\VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'thumbnail_path' => $thumbnailPath,
                        'is_featured' => ($featuredIndex !== null && $featuredIndex == $index),
                        'order' => $order++,
                    ]);
                    
                    $newImageIds[] = $vehicleImage->id;
                }
            }
        }

        // Handle featured image for existing images
        if ($request->has('featured_image') && $request->featured_image) {
            // Check if it's an existing image ID (not starting with "new_")
            if (strpos($request->featured_image, 'new_') !== 0) {
                // It's an existing image ID
                $featuredImage = \App\Models\VehicleImage::find($request->featured_image);
                if ($featuredImage && $featuredImage->vehicle_id == $vehicle->id) {
                    $featuredImage->update(['is_featured' => true]);
                }
            }
        } else {
            // Jika tidak ada yang dipilih, set foto pertama sebagai featured
            $firstImage = $vehicle->vehicleImages()->first();
            if ($firstImage) {
                $firstImage->update(['is_featured' => true]);
            }
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil diperbarui!');
    }

    public function destroy(Vehicle $vehicle)
    {
        // Check if vehicle has active bookings
        $hasActiveBookings = $vehicle->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasActiveBookings) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kendaraan yang memiliki booking aktif');
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus');
    }

    public function setQueueNumber(Vehicle $vehicle)
    {
        // Get the last queue number
        $lastQueueNumber = Vehicle::whereNotNull('queue_number')
            ->max('queue_number') ?? 0;
        
        // Set vehicle queue number to one after the last
        $vehicle->update([
            'queue_number' => $lastQueueNumber + 1
        ]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Nomor antrian kendaraan berhasil diset ke ' . ($lastQueueNumber + 1));
    }
}
