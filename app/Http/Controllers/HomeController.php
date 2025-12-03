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
        $vehicles = Vehicle::with(['category', 'vehicleImages'])
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'asc')
            ->get();
        
        // Expand vehicles: if category has both prices, duplicate the vehicle
        $expandedVehicles = collect();
        foreach ($vehicles as $vehicle) {
            $category = $vehicle->category;
            
            // Check if category has both prices
            $hasPrice = $category->price && $category->price > 0;
            $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
            
            if ($hasPrice && $hasPriceWithDriver) {
                // Add vehicle twice: once for price, once for price_with_driver
                // First: without driver (should appear first)
                $vehicleWithoutDriver = clone $vehicle;
                $vehicleWithoutDriver->display_price = $category->price;
                $vehicleWithoutDriver->price_type = 'without_driver';
                $vehicleWithoutDriver->sort_order = $vehicle->queue_number * 10; // Multiply by 10 to allow insertion
                $expandedVehicles->push($vehicleWithoutDriver);
                
                // Second: with driver
                $vehicleWithDriver = clone $vehicle;
                $vehicleWithDriver->display_price = $category->price_with_driver;
                $vehicleWithDriver->price_type = 'with_driver';
                $vehicleWithDriver->sort_order = $vehicle->queue_number * 10 + 1; // +1 to appear after without_driver
                $expandedVehicles->push($vehicleWithDriver);
            } elseif ($hasPrice) {
                // Only price without driver
                $vehicle->display_price = $category->price;
                $vehicle->price_type = 'without_driver';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            } elseif ($hasPriceWithDriver) {
                // Only price with driver
                $vehicle->display_price = $category->price_with_driver;
                $vehicle->price_type = 'with_driver';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            } else {
                // Use vehicle's own price_per_day if category has no price
                $vehicle->display_price = $vehicle->price_per_day;
                $vehicle->price_type = 'default';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            }
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