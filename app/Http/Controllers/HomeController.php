<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        // Get popular vehicles
        // Only show vehicles with queue_number, ordered by queue_number (ascending)
        $vehicles = Vehicle::with(['category', 'vehicleImages', 'brand', 'rentalCategories.rentalCategory'])
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'asc')
            ->get();
        
        // Expand vehicles: prioritize vehicle_rental_categories price, fallback to category price
        // Only show vehicles that have at least one price set
        $expandedVehicles = collect();
        foreach ($vehicles as $vehicle) {
            $category = $vehicle->category;
            $rentalCategories = $vehicle->rentalCategories;
            
            // Check if vehicle has rental categories with prices
            $hasRentalCategoryPrices = $rentalCategories->where('price', '>', 0)->count() > 0;
            
            if ($hasRentalCategoryPrices) {
                // Use prices from vehicle_rental_categories
                foreach ($rentalCategories as $index => $vehicleRentalCategory) {
                    if ($vehicleRentalCategory->price && $vehicleRentalCategory->price > 0) {
                        $rentalCategory = $vehicleRentalCategory->rentalCategory;
                        if ($rentalCategory) {
                            $vehicleClone = clone $vehicle;
                            $vehicleClone->display_price = $vehicleRentalCategory->price;
                            $vehicleClone->rental_category_name = $rentalCategory->name;
                            $vehicleClone->rental_category_id = $rentalCategory->id;
                            $vehicleClone->price_type = $vehicleRentalCategory->with_driver ? 'with_driver' : 'without_driver';
                            $vehicleClone->price_label = $vehicleRentalCategory->with_driver ? 'Dengan Sopir' : 'Tanpa Sopir';
                            $vehicleClone->sort_order = $vehicle->queue_number * 10 + $index;
                            $expandedVehicles->push($vehicleClone);
                        }
                    }
                }
            } else {
                // Fallback to category prices if no rental category prices
                $hasPrice = $category->price && $category->price > 0;
                $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
                
                if ($hasPrice && $hasPriceWithDriver) {
                    // Add vehicle twice: once for price, once for price_with_driver
                    // First: without driver (should appear first)
                    $vehicleWithoutDriver = clone $vehicle;
                    $vehicleWithoutDriver->display_price = $category->price;
                    $vehicleWithoutDriver->price_type = 'without_driver';
                    $vehicleWithoutDriver->price_label = 'Tanpa Sopir';
                    $vehicleWithoutDriver->rental_category_name = null;
                    $vehicleWithoutDriver->sort_order = $vehicle->queue_number * 10;
                    $expandedVehicles->push($vehicleWithoutDriver);
                    
                    // Second: with driver
                    $vehicleWithDriver = clone $vehicle;
                    $vehicleWithDriver->display_price = $category->price_with_driver;
                    $vehicleWithDriver->price_type = 'with_driver';
                    $vehicleWithDriver->price_label = 'Dengan Sopir';
                    $vehicleWithDriver->rental_category_name = null;
                    $vehicleWithDriver->sort_order = $vehicle->queue_number * 10 + 1;
                    $expandedVehicles->push($vehicleWithDriver);
                } elseif ($hasPrice) {
                    // Only price without driver
                    $vehicle->display_price = $category->price;
                    $vehicle->price_type = 'without_driver';
                    $vehicle->price_label = 'Tanpa Sopir';
                    $vehicle->rental_category_name = null;
                    $vehicle->sort_order = $vehicle->queue_number * 10;
                    $expandedVehicles->push($vehicle);
                } elseif ($hasPriceWithDriver) {
                    // Only price with driver
                    $vehicle->display_price = $category->price_with_driver;
                    $vehicle->price_type = 'with_driver';
                    $vehicle->price_label = 'Dengan Sopir';
                    $vehicle->rental_category_name = null;
                    $vehicle->sort_order = $vehicle->queue_number * 10;
                    $expandedVehicles->push($vehicle);
                }
            }
            // Skip vehicles that don't have any price set
        }
        
        // Sort expanded vehicles by sort_order (queue_number * 10 + offset)
        $expandedVehicles = $expandedVehicles->sortBy('sort_order')->values();
        
        // Limit to 6 for home page
        $popularVehicles = $expandedVehicles->take(6);

        // Get vehicle categories for quick search
        $categories = VehicleCategory::all();

        return view('home', compact('popularVehicles', 'categories'));
    }

    public function profile()
    {
        $user = Auth::user();
        $dataAnggota = $user->data_anggota ?? [];
        return view('profile', compact('user', 'dataAnggota'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nomor_hp' => 'nullable|string|max:20',
            'nomor_whatsapp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get existing data_anggota or create new array
        $dataAnggota = $user->data_anggota ?? [];
        
        // Update data anggota
        $dataAnggota['nomor_hp'] = $request->nomor_hp;
        $dataAnggota['nomor_whatsapp'] = $request->nomor_whatsapp;
        // Simpan alamat (jika ada alamat_rumah yang lama, tetap simpan untuk kompatibilitas)
        if ($request->alamat) {
            $dataAnggota['alamat'] = $request->alamat;
            $dataAnggota['alamat_rumah'] = $request->alamat; // Juga simpan sebagai alamat_rumah untuk kompatibilitas
        } else {
            // Jika kosong, hapus dari data
            unset($dataAnggota['alamat']);
            unset($dataAnggota['alamat_rumah']);
        }

        // Save to database
        $user->data_anggota = $dataAnggota;
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}