<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleOffDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua kendaraan milik member
        $vehicleIds = Vehicle::where('member_id', $user->id)->pluck('id');
        
        // Ambil semua booking untuk kendaraan milik member (dibuat oleh customer atau admin)
        $query = Booking::with(['vehicle.vehicleImages'])
            ->whereIn('vehicle_id', $vehicleIds);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('end_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $vehicles = Vehicle::where('member_id', $user->id)->get();
        
        return view('member.dashboard', compact('bookings', 'vehicles'));
    }

    // Profil Anggota
    public function profileIndex()
    {
        $user = Auth::user();
        $dataAnggota = $user->data_anggota ?? [];
        
        return view('member.profile.index', compact('user', 'dataAnggota'));
    }

    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nomor_whatsapp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat_rumah' => 'nullable|string|max:500',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'kategori_anggota' => 'nullable|array',
            'kategori_anggota.*' => 'in:member,driver',
            'sim' => 'nullable|array',
            'sim.*' => 'in:A,B1,B2,C',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle foto profil upload
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Hapus foto lama jika ada
            if ($user->foto_profil && \Illuminate\Support\Facades\Storage::disk('s3')->exists($user->foto_profil)) {
                \Illuminate\Support\Facades\Storage::disk('s3')->delete($user->foto_profil);
            }
            
            // Upload ke S3
            $path = $file->storeAs('profiles', $filename, 's3');
            $user->foto_profil = $path;
        }

        // Update nomor whatsapp
        $user->nomor_whatsapp = $request->nomor_whatsapp;

        // Update data anggota (JSON)
        $dataAnggota = [
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat_rumah' => $request->alamat_rumah,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kategori_anggota' => $request->kategori_anggota ?? [],
            'sim' => $request->sim ?? [],
        ];

        $user->data_anggota = $dataAnggota;
        $user->save();

        return redirect()->route('member.profile.index')->with('success', 'Profil anggota berhasil diperbarui!');
    }

    // Daftar Kendaraan
    public function vehiclesIndex(Request $request)
    {
        $user = Auth::user();
        
        // Hanya tampilkan kendaraan yang didaftarkan oleh member ini
        $query = Vehicle::with(['category', 'vehicleImages'])
            ->where('member_id', $user->id);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%')
                  ->orWhere('plate_number', 'like', '%' . $request->search . '%');
            });
        }

        $vehicles = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('member.vehicles.index', compact('vehicles'));
    }

    public function vehiclesCreate()
    {
        $categories = \App\Models\VehicleCategory::all();
        return view('member.vehicles.create', compact('categories'));
    }

    public function vehiclesStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_image' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        $vehicle = Vehicle::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name . '-' . uniqid()),
            'category_id' => $request->category_id,
            'member_id' => $user->id,
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
            'is_available' => $request->has('is_available'),
        ]);

        // Handle image uploads - Upload ke S3 dengan fallback ke local
        if ($request->hasFile('images')) {
            $order = 0;
            $featuredIndex = (int)($request->featured_image ?? 0);
            
            foreach ($request->file('images') as $index => $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = null;
                
                // Coba upload ke S3 terlebih dahulu
                try {
                    $path = $image->storeAs('vehicles', $filename, 's3');
                } catch (\Exception $e) {
                    // Jika S3 gagal, fallback ke local storage
                    try {
                        // Pastikan direktori ada
                        $dir = public_path('images/vehicles');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $image->move($dir, $filename);
                        $path = 'images/vehicles/' . $filename;
                    } catch (\Exception $e2) {
                        // Jika local juga gagal, skip gambar ini
                        \Log::error('Failed to upload vehicle image: ' . $e2->getMessage());
                        continue;
                    }
                }
                
                // Hanya simpan jika path berhasil
                if ($path) {
                    // Buat thumbnail
                    $thumbnailPath = \App\Helpers\ImageHelper::createThumbnail($path);
                    
                    \App\Models\VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'thumbnail_path' => $thumbnailPath,
                        'is_featured' => ($featuredIndex == $index),
                        'order' => $order++,
                    ]);
                }
            }
            
            // Jika tidak ada yang dipilih sebagai featured, set foto pertama
            if ($vehicle->vehicleImages()->where('is_featured', true)->count() == 0) {
                $firstImage = $vehicle->vehicleImages()->first();
                if ($firstImage) {
                    $firstImage->update(['is_featured' => true]);
                }
            }
        }

        return redirect()->route('member.vehicles.index')->with('success', 'Kendaraan berhasil ditambahkan!');
    }

    public function vehiclesEdit(Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Pastikan kendaraan milik member yang login
        if ($vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $categories = \App\Models\VehicleCategory::all();
        $vehicle->load('vehicleImages');
        
        return view('member.vehicles.edit', compact('vehicle', 'categories'));
    }

    public function vehiclesUpdate(Request $request, Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Pastikan kendaraan milik member yang login
        if ($vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:vehicle_categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|string',
            'transmission' => 'required|string',
            'seats' => 'required|integer|min:1|max:60',
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'features' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'featured_image' => 'nullable|integer',
            'delete_images' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vehicle->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name . '-' . $vehicle->id),
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
            'is_available' => $request->has('is_available'),
        ]);

        // Handle delete images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = \App\Models\VehicleImage::find($imageId);
                if ($image && $image->vehicle_id == $vehicle->id) {
                    // Normalize path untuk S3
                    $s3Path = $image->image_path;
                    if (strpos($s3Path, 'images/vehicles/') === 0) {
                        $s3Path = str_replace('images/vehicles/', 'vehicles/', $s3Path);
                    }
                    
                    // Hapus file dari S3 (jika path adalah S3 path)
                    if (preg_match('/^(vehicles|profiles|testimonials)\//', $s3Path)) {
                        try {
                            if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($s3Path)) {
                                \Illuminate\Support\Facades\Storage::disk('s3')->delete($s3Path);
                            }
                        } catch (\Exception $e) {
                            // Ignore error jika S3 belum dikonfigurasi
                        }
                    }
                    
                    // Fallback: hapus dari local jika masih ada
                    $filePath = public_path($image->image_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $image->delete();
                }
            }
        }

        // Handle new image uploads - Upload ke S3 dengan fallback ke local
        if ($request->hasFile('images')) {
            $maxOrder = $vehicle->vehicleImages()->max('order') ?? -1;
            $order = $maxOrder + 1;
            
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = null;
                
                // Coba upload ke S3 terlebih dahulu
                try {
                    $path = $image->storeAs('vehicles', $filename, 's3');
                } catch (\Exception $e) {
                    // Jika S3 gagal, fallback ke local storage
                    try {
                        // Pastikan direktori ada
                        $dir = public_path('images/vehicles');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        $image->move($dir, $filename);
                        $path = 'images/vehicles/' . $filename;
                    } catch (\Exception $e2) {
                        // Jika local juga gagal, skip gambar ini
                        \Log::error('Failed to upload vehicle image: ' . $e2->getMessage());
                        continue;
                    }
                }
                
                // Hanya simpan jika path berhasil
                if ($path) {
                    // Buat thumbnail
                    $thumbnailPath = \App\Helpers\ImageHelper::createThumbnail($path);
                    
                    \App\Models\VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'image_path' => $path,
                        'thumbnail_path' => $thumbnailPath,
                        'is_featured' => false,
                        'order' => $order++,
                    ]);
                }
            }
        }

        // Handle featured image (ID dari existing image)
        // Reset all featured first
        $vehicle->vehicleImages()->update(['is_featured' => false]);
        
        if ($request->has('featured_image') && $request->featured_image) {
            // Set new featured (harus ID dari existing image)
            $featuredImage = \App\Models\VehicleImage::find($request->featured_image);
            if ($featuredImage && $featuredImage->vehicle_id == $vehicle->id) {
                $featuredImage->update(['is_featured' => true]);
            }
        } else {
            // Jika tidak ada yang dipilih, set foto pertama sebagai featured
            $firstImage = $vehicle->vehicleImages()->first();
            if ($firstImage) {
                $firstImage->update(['is_featured' => true]);
            }
        }

        return redirect()->route('member.vehicles.index')->with('success', 'Kendaraan berhasil diperbarui!');
    }

    public function vehiclesDestroy(Vehicle $vehicle)
    {
        $user = Auth::user();
        
        // Pastikan kendaraan milik member yang login
        if ($vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        // Delete images
        foreach ($vehicle->vehicleImages as $image) {
            // Normalize path untuk S3
            $s3Path = $image->image_path;
            if (strpos($s3Path, 'images/vehicles/') === 0) {
                $s3Path = str_replace('images/vehicles/', 'vehicles/', $s3Path);
            }
            
            // Hapus file dari S3 (jika path adalah S3 path)
            if (preg_match('/^(vehicles|profiles|testimonials)\//', $s3Path)) {
                try {
                    if (\Illuminate\Support\Facades\Storage::disk('s3')->exists($s3Path)) {
                        \Illuminate\Support\Facades\Storage::disk('s3')->delete($s3Path);
                    }
                } catch (\Exception $e) {
                    // Ignore error jika S3 belum dikonfigurasi
                }
            }
            
            // Fallback: hapus dari local jika masih ada
            $filePath = public_path($image->image_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
        }

        $vehicle->delete();

        return redirect()->route('member.vehicles.index')->with('success', 'Kendaraan berhasil dihapus!');
    }

    public function vehiclesShow($slug)
    {
        $vehicle = Vehicle::with(['category', 'vehicleImages'])->where('slug', $slug)->firstOrFail();
        
        $relatedVehicles = Vehicle::with(['category'])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->where('is_available', true)
            ->limit(3)
            ->get();

        return view('member.vehicles.show', compact('vehicle', 'relatedVehicles'));
    }

    // Manajemen Akun
    public function accountIndex()
    {
        $user = Auth::user();
        return view('member.account.index', compact('user'));
    }

    public function accountUpdate(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('member.account.index')->with('success', 'Profil berhasil diperbarui!');
    }

    public function accountChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('member.account.index')->with('success', 'Password berhasil diubah!');
    }

    // Hari Off
    public function offDaysIndex(Request $request)
    {
        $user = Auth::user();
        
        // Ambil semua kendaraan milik member
        $vehicleIds = Vehicle::where('member_id', $user->id)->pluck('id');
        
        // Ambil semua hari off untuk kendaraan milik member
        $query = VehicleOffDay::with(['vehicle.vehicleImages'])
            ->whereIn('vehicle_id', $vehicleIds)
            ->where(function($q) {
                // Hanya tampilkan yang masih aktif (end_date >= hari ini)
                $q->where('end_date', '>=', now()->toDateString());
            });

        // Filter by vehicle
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('end_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $offDays = $query->orderBy('start_date', 'asc')->paginate(15);
        $vehicles = Vehicle::where('member_id', $user->id)->get();

        return view('member.off-days.index', compact('offDays', 'vehicles'));
    }

    public function offDaysCreate()
    {
        $user = Auth::user();
        $vehicles = Vehicle::where('member_id', $user->id)->get();

        return view('member.off-days.create', compact('vehicles'));
    }

    public function offDaysStore(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        // Validasi bahwa kendaraan milik member yang login
        if ($request->has('vehicle_id')) {
            $vehicle = Vehicle::find($request->vehicle_id);
            if (!$vehicle || $vehicle->member_id != $user->id) {
                $validator->errors()->add('vehicle_id', 'Kendaraan tidak ditemukan atau bukan milik Anda.');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah ada konflik dengan hari off yang sudah ada
        $conflictingOffDay = VehicleOffDay::where('vehicle_id', $request->vehicle_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($conflictingOffDay) {
            return redirect()->back()
                ->withErrors(['dates' => 'Tanggal yang dipilih sudah ada hari off yang tumpang tindih.'])
                ->withInput();
        }

        VehicleOffDay::create([
            'vehicle_id' => $request->vehicle_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('member.off-days.index')
            ->with('success', 'Hari off berhasil ditambahkan!');
    }

    public function offDaysEdit(VehicleOffDay $offDay)
    {
        $user = Auth::user();
        
        // Pastikan hari off milik kendaraan member yang login
        if ($offDay->vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $vehicles = Vehicle::where('member_id', $user->id)->get();

        return view('member.off-days.edit', compact('offDay', 'vehicles'));
    }

    public function offDaysUpdate(Request $request, VehicleOffDay $offDay)
    {
        $user = Auth::user();

        // Pastikan hari off milik kendaraan member yang login
        if ($offDay->vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:500',
        ]);

        // Validasi bahwa kendaraan milik member yang login
        if ($request->has('vehicle_id')) {
            $vehicle = Vehicle::find($request->vehicle_id);
            if (!$vehicle || $vehicle->member_id != $user->id) {
                $validator->errors()->add('vehicle_id', 'Kendaraan tidak ditemukan atau bukan milik Anda.');
            }
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah ada konflik dengan hari off yang sudah ada (kecuali hari off yang sedang diupdate)
        $conflictingOffDay = VehicleOffDay::where('vehicle_id', $request->vehicle_id)
            ->where('id', '!=', $offDay->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($conflictingOffDay) {
            return redirect()->back()
                ->withErrors(['dates' => 'Tanggal yang dipilih sudah ada hari off yang tumpang tindih.'])
                ->withInput();
        }

        $offDay->update([
            'vehicle_id' => $request->vehicle_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
        ]);

        return redirect()->route('member.off-days.index')
            ->with('success', 'Hari off berhasil diperbarui!');
    }

    public function offDaysDestroy(VehicleOffDay $offDay)
    {
        $user = Auth::user();

        // Pastikan hari off milik kendaraan member yang login
        if ($offDay->vehicle->member_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $offDay->delete();

        return redirect()->route('member.off-days.index')
            ->with('success', 'Hari off berhasil dihapus!');
    }

    // Daftar Member
    public function membersIndex(Request $request)
    {
        $query = User::where('role', 'member');

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nomor_whatsapp', 'like', '%' . $search . '%');
            });
        }

        $members = $query->orderBy('name', 'asc')->paginate(15);

        return view('member.members.index', compact('members'));
    }
}

