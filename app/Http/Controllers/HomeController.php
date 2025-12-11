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
        // Get 6 vehicles with queue_number, ordered by queue_number (ascending)
        // Only show vehicles that are available and have at least one price set
        $popularVehicles = Vehicle::with(['category', 'vehicleImages', 'brand'])
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->where(function($query) {
                $query->whereNotNull('price_per_day')
                      ->orWhereNotNull('price_per_day_no_driver');
            })
            ->orderBy('queue_number', 'asc')
            ->limit(6)
            ->get()
            ->map(function($vehicle) {
                // Set display price - prioritize price_per_day_no_driver, fallback to price_per_day
                if ($vehicle->price_per_day_no_driver && $vehicle->price_per_day_no_driver > 0) {
                    $vehicle->display_price = $vehicle->price_per_day_no_driver;
                    $vehicle->price_type = 'without_driver';
                    $vehicle->price_label = 'Tanpa Sopir';
                } elseif ($vehicle->price_per_day && $vehicle->price_per_day > 0) {
                    $vehicle->display_price = $vehicle->price_per_day;
                    $vehicle->price_type = 'with_driver';
                    $vehicle->price_label = 'Dengan Sopir';
                }
                return $vehicle;
            });

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