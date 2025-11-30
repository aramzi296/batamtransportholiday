<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleAvailability;
use Illuminate\Http\Request;

class AdminAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleAvailability::with('vehicle');
        
        // Filter by vehicle if specified
        if ($request->has('vehicle_id') && $request->vehicle_id) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        
        // Filter by date range if specified
        if ($request->has('date_from') && $request->date_from) {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('date', '<=', $request->date_to);
        }
        
        // Filter by availability status
        if ($request->has('is_available') && $request->is_available !== '') {
            $query->where('is_available', $request->is_available);
        }
        
        $availabilities = $query->orderBy('date', 'desc')
            ->paginate(15);
        
        $vehicles = Vehicle::orderBy('name')->get();
        
        return view('admin.availability.index', compact('availabilities', 'vehicles'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'date' => 'required|date|after_or_equal:today',
            'is_available' => 'required|boolean',
            'reason' => 'nullable|string|max:255'
        ]);
        
        // Check if availability record already exists for this vehicle and date
        $existingRecord = VehicleAvailability::where('vehicle_id', $request->vehicle_id)
            ->where('date', $request->date)
            ->first();
        
        if ($existingRecord) {
            // Update existing record
            $existingRecord->update([
                'is_available' => $request->is_available,
                'reason' => $request->reason
            ]);
            
            return redirect()->route('admin.availability.index')
                ->with('success', 'Ketersediaan kendaraan berhasil diperbarui');
        } else {
            // Create new record
            VehicleAvailability::create([
                'vehicle_id' => $request->vehicle_id,
                'date' => $request->date,
                'is_available' => $request->is_available,
                'reason' => $request->reason
            ]);
            
            return redirect()->route('admin.availability.index')
                ->with('success', 'Ketersediaan kendaraan berhasil ditambahkan');
        }
    }
    
    public function destroy($id)
    {
        $availability = VehicleAvailability::findOrFail($id);
        
        // Check if there are any bookings on this date
        $hasBookings = $availability->vehicle->bookings()
            ->whereDate('pickup_date', '<=', $availability->date)
            ->whereDate('return_date', '>=', $availability->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
        
        if ($hasBookings) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus ketersediaan yang memiliki booking aktif pada tanggal tersebut');
        }
        
        $availability->delete();
        
        return redirect()->route('admin.availability.index')
            ->with('success', 'Ketersediaan kendaraan berhasil dihapus');
    }
}