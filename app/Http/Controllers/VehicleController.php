<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['category', 'vehicleImages'])->where('is_available', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by date availability
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<=', $startDate)
                                  ->where('end_date', '>=', $endDate);
                            });
                  });
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
        }

        // Sort by price
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('price_per_day', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('price_per_day', 'desc');
            } elseif ($request->sort === 'name') {
                $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $vehicles = $query->paginate(12);
        $categories = VehicleCategory::all();

        return view('vehicles.index', compact('vehicles', 'categories'));
    }

    public function show($slug)
    {
        $vehicle = Vehicle::with(['category', 'vehicleImages'])->where('slug', $slug)->firstOrFail();
        
        // Get related vehicles (same category)
        $relatedVehicles = Vehicle::with(['category', 'vehicleImages'])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->where('is_available', true)
            ->limit(3)
            ->get();

        return view('vehicles.show', compact('vehicle', 'relatedVehicles'));
    }
    
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $vehicle = Vehicle::findOrFail($id);
        $unavailableDates = $vehicle->getUnavailableDatesInRange($request->start_date, $request->end_date);
        
        return response()->json([
            'available' => empty($unavailableDates),
            'unavailable_dates' => $unavailableDates,
            'message' => empty($unavailableDates) 
                ? 'Kendaraan tersedia untuk tanggal yang dipilih' 
                : 'Kendaraan tidak tersedia pada beberapa tanggal'
        ]);
    }
}