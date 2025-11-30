<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['vehicle', 'user'])
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
            
        $vehicles = \App\Models\Vehicle::select('id', 'name')->get();
        
        return view('admin.bookings.index', compact('bookings', 'vehicles'));
    }
    
    public function show(Booking $booking)
    {
        $booking->load(['vehicle', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function update(Request $request, Booking $booking)
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
    
    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dikonfirmasi!');
    }
    
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dibatalkan!');
    }
}