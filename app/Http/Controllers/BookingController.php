<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['vehicle', 'user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $vehicle = Vehicle::with(['category', 'vehicleImages'])->findOrFail($request->vehicle_id);
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        // Calculate total days and price
        $totalDays = 1;
        $totalPrice = $vehicle->price_per_day;
        $availabilityWarnings = [];
        
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $totalDays = $start->diffInDays($end);
            if ($totalDays == 0) $totalDays = 1;
            $totalPrice = $totalDays * $vehicle->price_per_day;
            
            // Check availability for selected dates and show warnings
            $currentDate = $start->copy();
            while ($currentDate <= $end) {
                if (!$vehicle->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                    $availability = $vehicle->availability()->whereDate('date', $currentDate->format('Y-m-d'))->first();
                    $reason = $availability && $availability->reason ? $availability->reason : 'Tidak tersedia';
                    
                    $availabilityWarnings[] = [
                        'date' => $currentDate->format('d/m/Y'),
                        'reason' => $reason
                    ];
                }
                $currentDate->addDay();
            }
        }

        return view('bookings.create', compact('vehicle', 'startDate', 'endDate', 'totalDays', 'totalPrice', 'availabilityWarnings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        
        // Check vehicle availability
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Check if vehicle is available for the requested dates
        $conflictingBooking = Booking::where('vehicle_id', $vehicle->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()
                ->withErrors(['dates' => 'Kendaraan tidak tersedia untuk tanggal yang dipilih. Ada booking lain yang sedang berlangsung.'])
                ->withInput();
        }
        
        // Check vehicle availability from availability table
        $unavailableDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            if (!$vehicle->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                // Get the specific reason if available
                $availability = $vehicle->availability()->whereDate('date', $currentDate->format('Y-m-d'))->first();
                $reason = $availability && $availability->reason ? $availability->reason : 'Kendaraan tidak tersedia';
                
                $unavailableDates[] = [
                    'date' => $currentDate->format('d/m/Y'),
                    'reason' => $reason
                ];
            }
            $currentDate->addDay();
        }
        
        if (!empty($unavailableDates)) {
            $errorMessage = 'Kendaraan tidak tersedia pada tanggal berikut:<br>';
            foreach ($unavailableDates as $unavailableDate) {
                $errorMessage .= '• ' . $unavailableDate['date'] . ' - ' . $unavailableDate['reason'] . '<br>';
            }
            
            return redirect()->back()
                ->withErrors(['dates' => $errorMessage])
                ->withInput();
        }

        $totalDays = $startDate->diffInDays($endDate);
        if ($totalDays == 0) $totalDays = 1;

        $booking = Booking::create([
            'booking_code' => Booking::generateBookingCode(),
            'vehicle_id' => $vehicle->id,
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'daily_price' => $vehicle->price_per_day,
            'total_price' => $totalDays * $vehicle->price_per_day,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Block dates in VehicleCalendar for this booking
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            \App\Models\VehicleCalendar::updateOrCreate([
                'vehicle_id' => $vehicle->id,
                'date' => $currentDate->format('Y-m-d'),
            ], [
                'blocked_by' => 'booking',
                'reason' => null,
            ]);
            $currentDate->addDay();
        }

        // Send email notifications
        try {
            // Send confirmation email to customer
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
            
            // Send notification email to customer service
            Mail::to('customerservice@carrental.com')->send(new NewBookingNotification($booking));
            
            // Also send to admin email if different
            Mail::to('admin@carrental.com')->send(new NewBookingNotification($booking));
        } catch (\Exception $e) {
            // Log the error but don't stop the booking process
            Log::error('Failed to send booking emails: ' . $e->getMessage());
        }

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Booking request submitted successfully! We will contact you within 24 hours.');
    }

    public function show($id)
    {
        $booking = Booking::with(['vehicle', 'user'])->findOrFail($id);
        
        // Check if user can view this booking
        if (Auth::guest() || (Auth::user()->id != $booking->user_id && Auth::user()->role !== 'admin')) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }
}
