<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalVehicles = Vehicle::count();
        $totalBookings = Booking::count();
        $totalArticles = Article::count();
        
        $recentBookings = Booking::with(['vehicle'])
            ->latest()
            ->take(5)
            ->get();
            
        $availableVehicles = Vehicle::where('is_available', true)->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVehicles', 
            'totalBookings',
            'totalArticles',
            'recentBookings',
            'availableVehicles',
            'pendingBookings'
        ));
    }
    
    // Users Management
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    
    public function createUser()
    {
        return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,customer',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }
    
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,customer',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }
    
    public function deleteUser(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }
        
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
    
    // Categories Management
    public function categories()
    {
        $categories = Category::withCount('vehicles')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }
    
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', false),
        ]);
        
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }
    
    public function deleteCategory(Category $category)
    {
        if ($category->vehicles()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Tidak dapat menghapus kategori yang masih memiliki kendaraan!');
        }
        
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
    
    // Vehicles Management
    public function vehicles()
    {
        $vehicles = Vehicle::with('category')
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('brand', 'like', '%' . request('search') . '%')
                      ->orWhere('model', 'like', '%' . request('search') . '%');
            })
            ->when(request('category'), function($query) {
                $query->where('category_id', request('category'));
            })
            ->when(request('status') !== null, function($query) {
                $query->where('is_available', request('status'));
            })
            ->latest()
            ->paginate(10);
            
        $categories = Category::where('is_active', true)->get();
        
        return view('admin.vehicles.index', compact('vehicles', 'categories'));
    }
    
    public function createVehicle()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.vehicles.create', compact('categories'));
    }
    
    public function storeVehicle(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'seats' => 'required|integer|min:1|max:100',
            'plate_number' => 'required|string|max:255|unique:vehicles',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'is_available' => 'nullable|boolean',
        ]);
        
        Vehicle::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . uniqid()),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'plate_number' => $request->plate_number,
            'features' => $request->features ?? [],
            'images' => $request->images ?? [],
            'is_available' => $request->boolean('is_available', true),
        ]);
        
        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan!');
    }
    
    public function showVehicle(Vehicle $vehicle)
    {
        $vehicle->load(['category', 'bookings.customer']);
        return view('admin.vehicles.show', compact('vehicle'));
    }
    
    public function editVehicle(Vehicle $vehicle)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.vehicles.edit', compact('vehicle', 'categories'));
    }
    
    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'transmission' => 'required|string|max:255',
            'seats' => 'required|integer|min:1|max:100',
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $vehicle->id,
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'is_available' => 'nullable|boolean',
        ]);
        
        $vehicle->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . $vehicle->id),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'color' => $request->color,
            'fuel_type' => $request->fuel_type,
            'transmission' => $request->transmission,
            'seats' => $request->seats,
            'plate_number' => $request->plate_number,
            'features' => $request->features ?? [],
            'images' => $request->images ?? [],
            'is_available' => $request->boolean('is_available', false),
        ]);
        
        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil diperbarui!');
    }
    
    public function deleteVehicle(Vehicle $vehicle)
    {
        // Check if vehicle has bookings
        if ($vehicle->bookings()->count() > 0) {
            return redirect()->route('admin.vehicles.index')->with('error', 'Tidak dapat menghapus kendaraan yang memiliki riwayat booking!');
        }
        
        $vehicle->delete();
        return redirect()->route('admin.vehicles.index')->with('success', 'Kendaraan berhasil dihapus!');
    }
    
    // Bookings Management
    public function bookings()
    {
        $bookings = Booking::with(['vehicle'])
            ->when(request('search'), function($query) {
                $query->where('booking_code', 'like', '%' . request('search') . '%')
                      ->orWhere('customer_name', 'like', '%' . request('search') . '%')
                      ->orWhere('customer_email', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('vehicle_id'), function($query) {
                $query->where('vehicle_id', request('vehicle_id'));
            })
            ->latest()
            ->paginate(10);
            
        $vehicles = Vehicle::select('id', 'name')->get();
        
        return view('admin.bookings.index', compact('bookings', 'vehicles'));
    }
    
    public function showBooking(Booking $booking)
    {
        $booking->load(['vehicle']);
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string',
        ]);
        
        $booking->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);
        
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Status booking berhasil diperbarui!');
    }
}