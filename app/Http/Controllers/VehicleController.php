<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['category', 'vehicleImages'])
            ->where('is_available', true)
            ->whereNotNull('queue_number');

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
            // Default: order by queue_number (ascending)
            $query->orderBy('queue_number', 'asc');
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // Get vehicles
        $vehicles = $query->get();
        
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
        
        // Filter by driver type (with_driver or without_driver)
        if ($request->filled('driver_type')) {
            if ($request->driver_type === 'with_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && $v->price_type === 'with_driver';
                });
            } elseif ($request->driver_type === 'without_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && ($v->price_type === 'without_driver' || $v->price_type === 'default');
                });
            }
        }
        
        // Filter by price range
        if ($request->filled('price_min')) {
            $expandedVehicles = $expandedVehicles->filter(function($v) use ($request) {
                return ($v->display_price ?? $v->price_per_day) >= $request->price_min;
            });
        }
        if ($request->filled('price_max')) {
            $expandedVehicles = $expandedVehicles->filter(function($v) use ($request) {
                return ($v->display_price ?? $v->price_per_day) <= $request->price_max;
            });
        }
        
        // Sort expanded vehicles by sort_order (queue_number * 10 + offset)
        $expandedVehicles = $expandedVehicles->sortBy('sort_order')->values();
        
        // Paginate manually
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $items = $expandedVehicles->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $expandedVehicles->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        
        // Get unique models for filter
        $models = Vehicle::where('is_available', true)
            ->whereNotNull('queue_number')
            ->distinct()
            ->orderBy('model')
            ->pluck('model')
            ->filter()
            ->values();

        return view('vehicles.index', [
            'vehicles' => $paginated, 
            'categories' => $categories,
            'brands' => $brands,
            'models' => $models
        ]);
    }

    public function show($slug)
    {
        $vehicle = Vehicle::with(['category', 'vehicleImages'])->where('slug', $slug)->firstOrFail();
        
        // Get related vehicles (same category)
        $relatedVehicles = Vehicle::with(['category', 'vehicleImages'])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'asc')
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