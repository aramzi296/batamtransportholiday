<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use Livewire\Component;
use Livewire\WithPagination;

class VehiclesList extends Component
{
    use WithPagination;

    // Filter properties
    public $driver_type = '';
    public $category = '';
    public $brand_id = '';
    public $model = '';
    public $seats = '';
    public $price_max = null;
    public $search = '';
    public $sort = 'price_low';
    public $totalVehicles = 0;
    
    public function mount()
    {
        // Initialize price max from query string or set to null
        if (request()->has('price_max') && request()->get('price_max') !== '') {
            $this->price_max = (int) request()->get('price_max');
        }
    }

    protected $queryString = [
        'driver_type' => ['except' => ''],
        'category' => ['except' => ''],
        'brand_id' => ['except' => ''],
        'model' => ['except' => ''],
        'seats' => ['except' => ''],
        'price_max' => ['except' => null],
        'search' => ['except' => ''],
        'sort' => ['except' => 'price_low'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingBrandId()
    {
        $this->resetPage();
    }

    public function updatingModel()
    {
        $this->resetPage();
    }

    public function updatingSeats()
    {
        $this->resetPage();
    }

    public function updatingPriceMax()
    {
        $this->resetPage();
    }

    public function updatingDriverType()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->driver_type = '';
        $this->category = '';
        $this->brand_id = '';
        $this->model = '';
        $this->seats = '';
        $this->price_max = null;
        $this->search = '';
        $this->sort = 'price_low';
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'livewire.pagination.bootstrap-5';
    }

    public function getPriceRangeProperty()
    {
        // Get all available vehicles to calculate price range
        // Only use prices set by admin in category (price or price_with_driver)
        $vehicles = Vehicle::with(['category'])
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->get();
        
        $prices = collect();
        foreach ($vehicles as $vehicle) {
            $category = $vehicle->category;
            $hasPrice = $category->price && $category->price > 0;
            $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
            
            // Only add prices that are set by admin in category
            if ($hasPrice) {
                $prices->push($category->price);
            }
            if ($hasPriceWithDriver) {
                $prices->push($category->price_with_driver);
            }
            // Don't use price_per_day - only use prices set in category
        }
        
        $min = $prices->min() ?? 0;
        $max = $prices->max() ?? 10000000; // Default max 10 juta
        
        return [
            'min' => (int) floor($min / 10000) * 10000, // Round down to nearest 10k
            'max' => (int) ceil($max / 10000) * 10000,  // Round up to nearest 10k
        ];
    }

    public function render()
    {
        $query = Vehicle::with(['category', 'vehicleImages', 'brand'])
            ->where('is_available', true)
            ->whereNotNull('queue_number');

        // Filter by category
        if ($this->category) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        // Search by name, brand, or model
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('brand', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%')
                  ->orWhereHas('brand', function ($brandQuery) {
                      $brandQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by brand
        if ($this->brand_id) {
            $query->where('brand_id', $this->brand_id);
        }

        // Filter by model
        if ($this->model) {
            $query->where('model', 'like', '%' . $this->model . '%');
        }

        // Filter by seats (max penumpang - filter where seats <= selected value)
        if ($this->seats) {
            $query->where('seats', '<=', (int) $this->seats);
        }

        // Order by queue_number
        $query->orderBy('queue_number', 'asc');

        // Get vehicles
        $vehicles = $query->get();
        
        // Expand vehicles: create entries for with_driver and without_driver prices
        // Only show vehicles that have at least one price set
        $expandedVehicles = collect();
        foreach ($vehicles as $vehicle) {
            $hasPriceWithDriver = $vehicle->price_per_day && $vehicle->price_per_day > 0;
            $hasPriceWithoutDriver = $vehicle->price_per_day_no_driver && $vehicle->price_per_day_no_driver > 0;
            
            if ($hasPriceWithDriver && $hasPriceWithoutDriver) {
                // Add vehicle twice: once for with driver, once for without driver
                // First: without driver
                $vehicleWithoutDriver = clone $vehicle;
                $vehicleWithoutDriver->display_price = $vehicle->price_per_day_no_driver;
                $vehicleWithoutDriver->price_type = 'without_driver';
                $vehicleWithoutDriver->price_label = 'Tanpa Sopir';
                $vehicleWithoutDriver->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicleWithoutDriver);
                
                // Second: with driver
                $vehicleWithDriver = clone $vehicle;
                $vehicleWithDriver->display_price = $vehicle->price_per_day;
                $vehicleWithDriver->price_type = 'with_driver';
                $vehicleWithDriver->price_label = 'Dengan Sopir';
                $vehicleWithDriver->sort_order = $vehicle->queue_number * 10 + 1;
                $expandedVehicles->push($vehicleWithDriver);
            } elseif ($hasPriceWithoutDriver) {
                // Only price without driver
                $vehicle->display_price = $vehicle->price_per_day_no_driver;
                $vehicle->price_type = 'without_driver';
                $vehicle->price_label = 'Tanpa Sopir';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            } elseif ($hasPriceWithDriver) {
                // Only price with driver
                $vehicle->display_price = $vehicle->price_per_day;
                $vehicle->price_type = 'with_driver';
                $vehicle->price_label = 'Dengan Sopir';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            }
            // Skip vehicles that don't have any price set
        }
        
        // Filter by driver type (with_driver or without_driver)
        if ($this->driver_type) {
            if ($this->driver_type === 'with_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && $v->price_type === 'with_driver';
                });
            } elseif ($this->driver_type === 'without_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && $v->price_type === 'without_driver';
                });
            }
        }
        
        // Filter by price max
        if ($this->price_max !== null && $this->price_max !== '') {
            $expandedVehicles = $expandedVehicles->filter(function($v) {
                return isset($v->display_price) && $v->display_price > 0 && $v->display_price <= $this->price_max;
            });
        }
        
        // Sort expanded vehicles
        if ($this->sort === 'price_low') {
            $expandedVehicles = $expandedVehicles->sortBy(function($v) {
                return [$v->display_price ?? 999999999, $v->sort_order];
            })->values();
        } elseif ($this->sort === 'price_high') {
            $expandedVehicles = $expandedVehicles->sortByDesc(function($v) {
                return [$v->display_price ?? 0, $v->sort_order];
            })->values();
        } elseif ($this->sort === 'name') {
            $expandedVehicles = $expandedVehicles->sortBy(function($v) {
                return [$v->name, $v->sort_order];
            })->values();
        } else {
            // Default: sort by display_price, then by sort_order
            $expandedVehicles = $expandedVehicles->sortBy(function($v) {
                return [$v->display_price ?? 999999999, $v->sort_order];
            })->values();
        }
        
        // Paginate collection using Livewire's WithPagination trait
        $perPage = 12;
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage('page');
        
        // Get items for current page
        $items = $expandedVehicles->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        // Create paginator instance
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $expandedVehicles->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
        
        // Set pagination path
        $paginated->setPath(request()->url());
        
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

        // Get unique seats for filter (grouped and sorted)
        $seatsOptions = Vehicle::where('is_available', true)
            ->whereNotNull('queue_number')
            ->whereNotNull('seats')
            ->distinct()
            ->orderBy('seats', 'asc')
            ->pluck('seats')
            ->filter()
            ->values();

        return view('livewire.vehicles-list', [
            'vehicles' => $paginated,
            'categories' => $categories,
            'brands' => $brands,
            'models' => $models,
            'seatsOptions' => $seatsOptions
        ]);
    }
}
