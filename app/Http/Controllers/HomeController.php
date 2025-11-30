<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get popular vehicles (limit to 6)
        $popularVehicles = Vehicle::with(['category', 'vehicleImages'])
            ->where('is_available', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get vehicle categories for quick search
        $categories = VehicleCategory::all();

        return view('home', compact('popularVehicles', 'categories'));
    }

    public function profile()
    {
        return view('profile');
    }
}