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
        // Get the selected vehicle
        $selectedVehicle = Vehicle::with(['category', 'vehicleImages'])->findOrFail($request->vehicle_id);
        
        // Get alternative vehicle: same category but with lower queue number (default selection)
        $alternativeVehicle = null;
        if ($selectedVehicle->queue_number) {
            $alternativeVehicle = Vehicle::with(['category', 'vehicleImages'])
                ->where('category_id', $selectedVehicle->category_id)
                ->where('id', '!=', $selectedVehicle->id)
                ->where('is_available', true)
                ->whereNotNull('queue_number')
                ->where('queue_number', '<', $selectedVehicle->queue_number)
                ->orderBy('queue_number', 'asc')
                ->first();
        }
        
        // If no alternative found, use the selected vehicle as default
        $defaultVehicle = $alternativeVehicle ?? $selectedVehicle;
        
        // Check if category has both prices
        $category = $defaultVehicle->category;
        $hasPrice = $category->price && $category->price > 0;
        $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
        $hasBothPrices = $hasPrice && $hasPriceWithDriver;
        
        // Determine default price and driver option
        $defaultPrice = $hasPrice ? $category->price : ($hasPriceWithDriver ? $category->price_with_driver : $defaultVehicle->price_per_day);
        $defaultWithDriver = $hasBothPrices ? false : ($hasPriceWithDriver ? true : false); // Default to without driver if both available
        
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $withDriver = $request->has('with_driver') ? $request->with_driver == '1' : $defaultWithDriver;
        
        // Calculate total days and price
        $totalDays = 1;
        $selectedPrice = $withDriver && $hasPriceWithDriver ? $category->price_with_driver : ($hasPrice ? $category->price : $defaultVehicle->price_per_day);
        $totalPrice = $selectedPrice;
        $availabilityWarnings = [];
        
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            $totalDays = $start->diffInDays($end);
            if ($totalDays == 0) $totalDays = 1;
            $totalPrice = $totalDays * $selectedPrice;
            
            // Check availability for selected dates and show warnings
            $currentDate = $start->copy();
            while ($currentDate <= $end) {
                if (!$defaultVehicle->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                    $availability = $defaultVehicle->availability()->whereDate('date', $currentDate->format('Y-m-d'))->first();
                    $reason = $availability && $availability->reason ? $availability->reason : 'Tidak tersedia';
                    
                    $availabilityWarnings[] = [
                        'date' => $currentDate->format('d/m/Y'),
                        'reason' => $reason
                    ];
                }
                $currentDate->addDay();
            }
        }

        return view('bookings.create', compact('selectedVehicle', 'alternativeVehicle', 'defaultVehicle', 'startDate', 'endDate', 'totalDays', 'totalPrice', 'availabilityWarnings', 'hasBothPrices', 'hasPrice', 'hasPriceWithDriver', 'category', 'withDriver'));
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

        // Determine price based on driver option
        $category = $vehicle->category;
        $withDriver = $request->has('with_driver') && $request->with_driver == '1';
        $hasPrice = $category->price && $category->price > 0;
        $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
        
        // Calculate daily price
        $dailyPrice = $vehicle->price_per_day; // Default to vehicle price
        if ($hasPrice && $hasPriceWithDriver) {
            // Both prices available, use selected option
            $dailyPrice = $withDriver ? $category->price_with_driver : $category->price;
        } elseif ($hasPriceWithDriver) {
            // Only price with driver available
            $dailyPrice = $category->price_with_driver;
            $withDriver = true;
        } elseif ($hasPrice) {
            // Only price without driver available
            $dailyPrice = $category->price;
            $withDriver = false;
        }

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
            'daily_price' => $dailyPrice,
            'total_price' => $totalDays * $dailyPrice,
            'with_driver' => $withDriver,
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
        $emailSent = false;
        try {
            // Send confirmation email to customer
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
            $emailSent = true;
            
            // Get admin emails from config
            $adminEmails = config('services.admin.emails', []);
            $adminEmail = config('services.admin.email');
            
            // Combine both: use admin_emails if available, fallback to admin_email
            $emailList = [];
            if (!empty($adminEmails) && is_array($adminEmails)) {
                $emailList = $adminEmails;
            }
            
            // Add single admin_email if set and not already in array
            if (!empty($adminEmail) && !in_array($adminEmail, $emailList)) {
                $emailList[] = $adminEmail;
            }
            
            // Fallback to default if no emails configured
            if (empty($emailList)) {
                $emailList = ['admin@dsarana.com'];
            }
            
            // Send notification email to all admin emails
            foreach ($emailList as $email) {
                try {
                    Mail::to($email)->send(new NewBookingNotification($booking));
                } catch (\Exception $e) {
                    Log::error('Failed to send booking email to admin: ' . $e->getMessage(), [
                        'email' => $email
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't stop the booking process
            Log::error('Failed to send booking emails: ' . $e->getMessage());
        }

        // Redirect to thank you page
        return redirect()->route('booking.thank-you', $booking->id)
            ->with('email_sent', $emailSent);
    }

    public function thankYou($id)
    {
        $booking = Booking::with(['vehicle.category'])->findOrFail($id);
        
        // Admin contact info (can be moved to config later)
        $adminContact = [
            'phone' => '+62 821 7086 0825',
            'phone2' => '+62 813 6481 0770',
            'phone3' => '+62 811 700 7201',
            'email' => 'admin@dsarana.com',
        ];
        
        // Check if user is logged in and owns this booking
        $isOwner = Auth::check() && Auth::id() == $booking->user_id;
        
        return view('bookings.thank-you', compact('booking', 'adminContact', 'isOwner'));
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
