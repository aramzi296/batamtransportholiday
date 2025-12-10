<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\VehicleCalendar;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
use App\Services\WhatsAppService;
use App\Http\Controllers\WhatsAppController;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingForm extends Component
{
    // Step management
    public $currentStep = 1;
    
    // Vehicle
    public $vehicle_id;
    public $vehicle;
    public $alternativeVehicles = [];
    public $showVehicleSelection = false;
    public $selectedVehicleForBooking = null;
    
    // Step 1: Date Selection
    public $start_date = null;
    public $rental_duration = 1; // Lama pemakaian dalam hari
    public $end_date = null;
    public $rental_category_id = null;
    public $with_driver = false;
    
    // Step 2: Customer Information
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';
    public $customer_address = '';
    public $notes = '';
    
    // Calculated values
    public $total_days = 0;
    public $daily_price = 0;
    public $total_price = 0;
    public $rental_category_name = null;
    
    // Availability
    public $unavailableDates = [];
    
    public function mount($vehicleId = null)
    {
        if ($vehicleId) {
            $this->vehicle_id = $vehicleId;
        } else {
            $this->vehicle_id = request()->get('vehicle_id');
        }
        
        // Get rental_category_id and with_driver from query parameter if provided
        $rentalCategoryId = request()->get('rental_category_id');
        if ($rentalCategoryId) {
            $this->rental_category_id = $rentalCategoryId;
        }
        
        $withDriverParam = request()->get('with_driver');
        if ($withDriverParam !== null) {
            $this->with_driver = $withDriverParam == '1' || $withDriverParam === true;
        }
        
        if ($this->vehicle_id) {
            $this->loadVehicle();
            $this->loadAlternativeVehicles();
        }
        
        // Pre-fill customer info if user is logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->customer_name = $user->name ?? '';
            $this->customer_email = $user->email ?? '';
            $dataAnggota = $user->data_anggota ?? [];
            $this->customer_phone = $dataAnggota['nomor_whatsapp'] ?? $dataAnggota['nomor_hp'] ?? '';
            $this->customer_address = $dataAnggota['alamat'] ?? $dataAnggota['alamat_rumah'] ?? '';
        }
    }
    
    public function loadVehicle()
    {
        $this->vehicle = Vehicle::with(['category', 'vehicleImages', 'brand'])
            ->findOrFail($this->vehicle_id);
        
        // Set selected vehicle for booking only if not already set
        if (!$this->selectedVehicleForBooking) {
            $this->selectedVehicleForBooking = $this->vehicle_id;
        }
        
        // Load unavailable dates
        $this->loadUnavailableDates();
        
        // Use price directly from vehicle based on with_driver
        if ($this->with_driver) {
            $this->daily_price = $this->vehicle->price_per_day ?? 0;
        } else {
            $this->daily_price = $this->vehicle->price_per_day_no_driver ?? $this->vehicle->price_per_day ?? 0;
        }
    }
    
    public function loadAlternativeVehicles()
    {
        if (!$this->vehicle) {
            return;
        }
        
        $selectedVehicle = $this->vehicle;
        
        // Build query for alternative vehicles
        $query = Vehicle::with(['category', 'vehicleImages', 'brand'])
            ->where('category_id', $selectedVehicle->category_id)
            ->where('seats', $selectedVehicle->seats)
            ->where('id', '!=', $selectedVehicle->id)
            ->where('is_available', true)
            ->whereNotNull('queue_number');
        
        // Filter by price based on with_driver
        if ($this->with_driver) {
            // If user selected with_driver, match price_per_day
            if ($selectedVehicle->price_per_day) {
                $query->where('price_per_day', $selectedVehicle->price_per_day);
            }
        } else {
            // If user selected without_driver, match price_per_day_no_driver
            if ($selectedVehicle->price_per_day_no_driver) {
                $query->where('price_per_day_no_driver', $selectedVehicle->price_per_day_no_driver);
            } elseif ($selectedVehicle->price_per_day) {
                // If no price_per_day_no_driver, match price_per_day
                $query->where('price_per_day', $selectedVehicle->price_per_day);
            }
        }
        
        // Order by queue_number and limit to 3
        $alternatives = $query->orderBy('queue_number', 'asc')
            ->limit(3)
            ->get();
        
        // Build all vehicles list: include selected vehicle if it matches criteria
        $allVehicles = collect();
        
        // Add alternatives
        foreach ($alternatives as $alt) {
            $allVehicles->push($alt);
        }
        
        // Check if selected vehicle should be included (matches same criteria as alternatives)
        // Selected vehicle matches if it has same category, seats, and price
        $selectedMatches = false;
        if ($alternatives->count() > 0) {
            // Get price from first alternative for comparison
            $firstAlt = $alternatives->first();
            $altPrice = null;
            if ($this->with_driver) {
                $altPrice = $firstAlt->price_per_day;
            } else {
                $altPrice = $firstAlt->price_per_day_no_driver ?? $firstAlt->price_per_day;
            }
            
            // Compare selected vehicle price with alternative price
            if ($this->with_driver) {
                $selectedMatches = $selectedVehicle->price_per_day == $altPrice;
            } else {
                $selectedPrice = $selectedVehicle->price_per_day_no_driver ?? $selectedVehicle->price_per_day;
                $selectedMatches = $selectedPrice == $altPrice;
            }
        }
        
        // Add selected vehicle if it matches criteria and not already in alternatives
        if ($selectedMatches && !$alternatives->contains('id', $selectedVehicle->id)) {
            $allVehicles->push($selectedVehicle);
        }
        
        // Sort by queue_number
        $allVehicles = $allVehicles->sortBy('queue_number')->values();
        
        // Store alternatives (max 4: 3 alternatives + selected if applicable)
        // Convert to array but ensure all fields are properly formatted
        $this->alternativeVehicles = $allVehicles->take(4)->map(function($vehicle) {
            // Get brand name safely
            $brandName = '';
            if (is_string($vehicle->brand)) {
                $brandName = $vehicle->brand;
            } elseif ($vehicle->brand && is_object($vehicle->brand)) {
                $brandName = $vehicle->brand->name ?? '';
            } elseif ($vehicle->brand_name) {
                $brandName = $vehicle->brand_name;
            }
            
            return [
                'id' => $vehicle->id,
                'name' => $vehicle->name ?? '',
                'brand' => $brandName,
                'model' => $vehicle->model ?? '',
                'year' => $vehicle->year ?? null,
                'price_per_day' => $vehicle->price_per_day ?? 0,
                'price_per_day_no_driver' => $vehicle->price_per_day_no_driver ?? null,
                'queue_number' => $vehicle->queue_number ?? null,
                'seats' => $vehicle->seats ?? null,
            ];
        })->toArray();
        
        // Determine if we should show vehicle selection
        // Only set selectedVehicleForBooking if not already set by user (in step 1)
        // If we're past step 1, don't change user's selection
        $userHasSelected = $this->currentStep > 1 || ($this->selectedVehicleForBooking && $this->selectedVehicleForBooking != $selectedVehicle->id);
        
        if ($alternatives->count() == 0) {
            // No alternatives, show only selected vehicle
            $this->showVehicleSelection = false;
            if (!$userHasSelected) {
                $this->selectedVehicleForBooking = $selectedVehicle->id;
            }
        } else {
            // Get minimum queue_number from all vehicles (alternatives + selected if included)
            $allQueueNumbers = $allVehicles->pluck('queue_number')->filter();
            $minQueueNumber = $allQueueNumbers->min();
            
            if ($selectedVehicle->queue_number && $selectedVehicle->queue_number <= $minQueueNumber) {
                // Selected vehicle has smallest queue_number, show only selected vehicle
                $this->showVehicleSelection = false;
                if (!$userHasSelected) {
                    $this->selectedVehicleForBooking = $selectedVehicle->id;
                }
            } else {
                // Show vehicle selection with alternatives
                $this->showVehicleSelection = true;
                
                // Only set default if user hasn't selected yet
                if (!$userHasSelected) {
                    // Default selection: vehicle with smallest queue_number
                    $defaultVehicle = $allVehicles->firstWhere('queue_number', $minQueueNumber);
                    $this->selectedVehicleForBooking = $defaultVehicle ? $defaultVehicle->id : $selectedVehicle->id;
                    
                    // If default is different from selected, update vehicle_id but don't reload to avoid loop
                    if ($this->selectedVehicleForBooking != $this->vehicle_id) {
                        // Update vehicle_id and reload vehicle data without calling loadAlternativeVehicles
                        $this->vehicle_id = $this->selectedVehicleForBooking;
                        
                        // Reload vehicle data
                        $this->vehicle = Vehicle::with(['category', 'vehicleImages', 'brand'])
                            ->findOrFail($this->vehicle_id);
                        $this->loadUnavailableDates();
                        
                        // Update price
                        if ($this->with_driver) {
                            $this->daily_price = $this->vehicle->price_per_day ?? 0;
                        } else {
                            $this->daily_price = $this->vehicle->price_per_day_no_driver ?? $this->vehicle->price_per_day ?? 0;
                        }
                    }
                }
            }
        }
    }
    
    public function updatedSelectedVehicleForBooking()
    {
        if ($this->selectedVehicleForBooking) {
            // Update vehicle_id to match selection
            $oldVehicleId = $this->vehicle_id;
            $this->vehicle_id = $this->selectedVehicleForBooking;
            
            // Only reload if vehicle changed
            if ($oldVehicleId != $this->vehicle_id) {
                $this->loadVehicle();
                // Preserve selectedVehicleForBooking (loadVehicle won't reset it now)
                $this->selectedVehicleForBooking = $this->vehicle_id;
                
                $this->calculatePrice();
                // Reset dates if needed
                if ($this->start_date) {
                    $this->calculateEndDate();
                }
            }
        }
    }
    
    
    public function loadUnavailableDates()
    {
        if (!$this->vehicle) return;
        
        $this->unavailableDates = [];
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addMonths(3); // Show 3 months ahead
        
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            if (!$this->vehicle->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                $this->unavailableDates[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }
    }
    
    public function updatedStartDate()
    {
        $this->calculateEndDate();
        $this->calculatePrice();
    }
    
    public function updatedRentalDuration()
    {
        // Validate rental duration
        if ($this->rental_duration < 1) {
            $this->rental_duration = 1;
        }
        if ($this->rental_duration > 365) {
            $this->rental_duration = 365;
        }
        
        $this->calculateEndDate();
        $this->calculatePrice();
    }
    
    public function calculateEndDate()
    {
        if ($this->start_date && $this->rental_duration > 0) {
            $start = Carbon::parse($this->start_date);
            $this->end_date = $start->copy()->addDays($this->rental_duration - 1)->format('Y-m-d');
        } else {
            $this->end_date = null;
        }
    }
    
    public function calculatePrice()
    {
        if (!$this->start_date || !$this->rental_duration || $this->rental_duration < 1) {
            $this->total_days = 0;
            $this->total_price = 0;
            return;
        }
        
        $this->total_days = $this->rental_duration;
        
        // Get daily price directly from vehicle
        $this->getCategoryPrice();
        
        $this->total_price = $this->total_days * $this->daily_price;
    }
    
    public function getCategoryPrice()
    {
        if (!$this->vehicle) return;
        
        // Use price directly from vehicle
        if ($this->with_driver) {
            $this->daily_price = $this->vehicle->price_per_day ?? 0;
        } else {
            $this->daily_price = $this->vehicle->price_per_day_no_driver ?? $this->vehicle->price_per_day ?? 0;
        }
        
        $this->rental_category_name = null;
    }
    
    public function updatedWithDriver()
    {
        $this->calculatePrice();
    }
    
    public function nextStep()
    {
        if ($this->currentStep == 1) {
            // Validate step 1: Pilih Kendaraan
            if (!$this->selectedVehicleForBooking) {
                $this->addError('selectedVehicleForBooking', 'Silakan pilih kendaraan');
                return;
            }
            
            // Ensure vehicle_id matches selected vehicle
            if ($this->vehicle_id != $this->selectedVehicleForBooking) {
                $this->vehicle_id = $this->selectedVehicleForBooking;
            }
            
            // Reload vehicle data based on selection
            $this->loadVehicle();
            // Don't reload alternatives here - they're only needed in step 1
            // The selected vehicle is already set by user
            
            // Ensure selectedVehicleForBooking is preserved
            $this->selectedVehicleForBooking = $this->vehicle_id;
            
            $this->currentStep = 2;
        } elseif ($this->currentStep == 2) {
            // Validate step 2: Pilih Tanggal
            if (!$this->start_date || !$this->end_date) {
                $this->addError('dates', 'Silakan pilih tanggal pemakaian');
                return;
            }
            
            // Validate dates are not in unavailable dates
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);
            $currentDate = $start->copy();
            
            while ($currentDate <= $end) {
                if (in_array($currentDate->format('Y-m-d'), $this->unavailableDates)) {
                    $this->addError('dates', 'Beberapa tanggal yang dipilih tidak tersedia');
                    return;
                }
                $currentDate->addDay();
            }
            
            $this->currentStep = 3;
        } elseif ($this->currentStep == 3) {
            // Validate step 3: Informasi Penyewa
            $this->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string',
                'notes' => 'nullable|string',
            ]);
            
            $this->currentStep = 4;
        }
    }
    
    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }
    
    public function confirmBooking()
    {
        try {
            // Ensure end_date is calculated
            $this->calculateEndDate();
            
            // Final validation
            $this->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'rental_duration' => 'required|integer|min:1',
                'notes' => 'nullable|string',
            ], [
                'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya.',
                'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
                'rental_duration.min' => 'Durasi pemakaian minimal 1 hari.',
            ]);
            
            // Check vehicle availability one more time
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
            
            $finalVehicleId = $this->selectedVehicleForBooking ?? $this->vehicle_id;
            $conflictingBooking = Booking::where('vehicle_id', $finalVehicleId)
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
                $this->addError('booking', 'Kendaraan tidak tersedia untuk tanggal yang dipilih. Ada booking lain yang sedang berlangsung.');
                return;
            }
            
            // Check unavailable dates - reload vehicle if needed
            $finalVehicleId = $this->selectedVehicleForBooking ?? $this->vehicle_id;
            if ($finalVehicleId != $this->vehicle_id) {
                $this->vehicle_id = $finalVehicleId;
                $this->loadVehicle();
            }
            
            $currentDate = $startDate->copy();
            $unavailableDates = [];
            while ($currentDate <= $endDate) {
                if (!$this->vehicle->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                    $unavailableDates[] = $currentDate->format('d/m/Y');
                }
                $currentDate->addDay();
            }
            
            if (!empty($unavailableDates)) {
                $this->addError('booking', 'Kendaraan tidak tersedia pada tanggal: ' . implode(', ', $unavailableDates));
                return;
            }
            
            // Calculate final price and end date
            $this->calculateEndDate();
            $this->calculatePrice();
            
            // Create booking - use selected vehicle for booking
            $finalVehicleId = $this->selectedVehicleForBooking ?? $this->vehicle_id;
            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'vehicle_id' => $finalVehicleId,
                'user_id' => Auth::id(),
                'customer_name' => $this->customer_name,
                'customer_email' => $this->customer_email,
                'customer_phone' => $this->customer_phone,
                'customer_address' => $this->customer_address,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $this->total_days,
                'daily_price' => $this->daily_price,
                'total_price' => $this->total_price,
                'with_driver' => $this->with_driver,
                'notes' => $this->notes,
                'status' => 'pending',
            ]);
            
            // Block dates in VehicleCalendar
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                VehicleCalendar::updateOrCreate([
                    'vehicle_id' => $finalVehicleId,
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
                Mail::to($this->customer_email)->send(new BookingConfirmation($booking));
                
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
                Log::error('Failed to send booking emails: ' . $e->getMessage());
            }
            
            // Send WhatsApp notification to admin
            try {
                $whatsappController = new WhatsAppController(new WhatsAppService());
                $whatsappController->sendAdminNotification($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send WhatsApp admin notification: ' . $e->getMessage());
                // Don't fail the booking if WhatsApp fails
            }
            
            // Redirect to thank you page
            session()->flash('booking_success', true);
            return redirect()->route('booking.thank-you', $booking->id);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so they're displayed
            throw $e;
        } catch (\Exception $e) {
            // Log and display other errors
            Log::error('Booking confirmation error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->addError('booking', 'Terjadi kesalahan saat memproses booking: ' . $e->getMessage() . '. Silakan coba lagi atau hubungi admin.');
        }
    }
    
    public function render()
    {
        if ($this->vehicle_id && !$this->vehicle) {
            $this->loadVehicle();
        }
        
        return view('livewire.booking-form');
    }
}
