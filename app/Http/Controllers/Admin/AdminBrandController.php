<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    public function index()
    {
        $brands = VehicleBrand::withCount('vehicles')->latest()->get();
        return view('admin.brands.index', compact('brands'));
    }
    
    public function create()
    {
        return view('admin.brands.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_brands',
            'is_active' => 'nullable|boolean',
        ]);
        
        VehicleBrand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil ditambahkan!');
    }
    
    public function show(VehicleBrand $brand)
    {
        $brand->load(['vehicles']);
        return view('admin.brands.show', compact('brand'));
    }
    
    public function edit(VehicleBrand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }
    
    public function update(Request $request, VehicleBrand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:vehicle_brands,name,' . $brand->id,
            'is_active' => 'nullable|boolean',
        ]);
        
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->boolean('is_active', false),
        ]);
        
        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil diperbarui!');
    }
    
    public function destroy(VehicleBrand $brand)
    {
        if ($brand->vehicles()->count() > 0) {
            return redirect()->route('admin.brands.index')->with('error', 'Tidak dapat menghapus merek yang masih memiliki kendaraan!');
        }
        
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Merek berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(VehicleBrand $brand)
    {
        try {
            $brand->update(['is_active' => !$brand->is_active]);
            
            $status = $brand->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "Merek berhasil {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status merek.');
        }
    }
}
