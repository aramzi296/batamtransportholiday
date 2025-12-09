<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRentalCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = RentalCategory::orderBy('sort_order')->latest()->get();
        return view('admin.rental-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rental-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rental_categories',
            'description' => 'nullable|string',
            'satuan' => 'required|string|in:jam,hari,setengah hari,bulan',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        RentalCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'satuan' => $request->satuan,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);
        
        return redirect()->route('admin.rental-categories.index')->with('success', 'Kategori sewa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalCategory $rentalCategory)
    {
        return view('admin.rental-categories.show', compact('rentalCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalCategory $rentalCategory)
    {
        return view('admin.rental-categories.edit', compact('rentalCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalCategory $rentalCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rental_categories,name,' . $rentalCategory->id,
            'description' => 'nullable|string',
            'satuan' => 'required|string|in:jam,hari,setengah hari,bulan',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        
        $rentalCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'satuan' => $request->satuan,
            'is_active' => $request->boolean('is_active', false),
            'sort_order' => $request->sort_order ?? 0,
        ]);
        
        return redirect()->route('admin.rental-categories.index')->with('success', 'Kategori sewa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalCategory $rentalCategory)
    {
        $rentalCategory->delete();
        return redirect()->route('admin.rental-categories.index')->with('success', 'Kategori sewa berhasil dihapus!');
    }
}
