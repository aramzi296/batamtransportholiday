<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminTestimonialController extends Controller
{
    /**
     * Display a listing of testimonials
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'featured') {
                $query->where('is_featured', true);
            }
        }

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $testimonials = $query->ordered()->paginate(10);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            $data = $validator->validated();
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');
            $data['display_order'] = $data['display_order'] ?? 0;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                
                // Upload ke public storage
                $path = $photo->storeAs('testimonials', $filename, 'public');
                $data['photo'] = $path;
            }

            $testimonial = Testimonial::create($data);

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil ditambahkan.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan testimoni: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified testimonial
     */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the testimonial
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified testimonial
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            $data = $validator->validated();
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');
            $data['display_order'] = $data['display_order'] ?? 0;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($testimonial->photo) {
                    if (Storage::disk('public')->exists($testimonial->photo)) {
                        Storage::disk('public')->delete($testimonial->photo);
                    }
                }

                $photo = $request->file('photo');
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                
                // Upload ke public storage
                $path = $photo->storeAs('testimonials', $filename, 'public');
                $data['photo'] = $path;
            }

            $testimonial->update($data);

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui testimoni: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified testimonial
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            // Delete photo if exists
            if ($testimonial->photo) {
                if (Storage::disk('public')->exists($testimonial->photo)) {
                    Storage::disk('public')->delete($testimonial->photo);
                }
            }

            $testimonial->delete();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'Testimoni berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus testimoni: ' . $e->getMessage());
        }
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_active' => !$testimonial->is_active]);
            
            $status = $testimonial->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()
                ->with('success', "Testimoni berhasil {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status testimoni.');
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_featured' => !$testimonial->is_featured]);
            
            $status = $testimonial->is_featured ? 'dijadikan unggulan' : 'dihapus dari unggulan';
            return redirect()->back()
                ->with('success', "Testimoni berhasil {$status}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah status unggulan testimoni.');
        }
    }
}