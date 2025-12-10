<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::withCount('vehicles')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_with_driver' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        VehicleCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'price_with_driver' => $request->price_with_driver,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }
    
    public function show(VehicleCategory $vehicleCategory)
    {
        $vehicleCategory->load(['vehicles']);
        $category = $vehicleCategory;
        return view('admin.categories.show', compact('category'));
    }
    
    public function edit(VehicleCategory $vehicleCategory)
    {
        $category = $vehicleCategory;
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, VehicleCategory $vehicleCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_categories,name,' . $vehicleCategory->id,
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_with_driver' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        
        $vehicleCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'price_with_driver' => $request->price_with_driver,
            'is_active' => $request->boolean('is_active', false),
        ]);
        
        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }
    
    public function destroy(VehicleCategory $vehicleCategory)
    {
        if ($vehicleCategory->vehicles()->count() > 0) {
            return redirect()->route('admin.vehicle-categories.index')->with('error', 'Tidak dapat menghapus kategori yang masih memiliki kendaraan!');
        }
        
        $vehicleCategory->delete();
        return redirect()->route('admin.vehicle-categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}