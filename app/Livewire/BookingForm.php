<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\VehicleCalendar;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
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
        $this->vehicle = Vehicle::with(['category', 'vehicleImages', 'brand', 'rentalCategories.rentalCategory'])
            ->findOrFail($this->vehicle_id);
        
        // Load unavailable dates
        $this->loadUnavailableDates();
        
        // Set rental category - use provided one or default to first available
        $rentalCategories = $this->vehicle->rentalCategories->where('price', '>', 0);
        if ($rentalCategories->count() > 0) {
            // If rental_category_id is already set (from query parameter), use it
            if ($this->rental_category_id) {
                $grouped = $rentalCategories->groupBy('rental_category_id');
                $selectedGroup = $grouped->get($this->rental_category_id);
                
                if ($selectedGroup && $selectedGroup->count() > 0) {
                    $firstRentalCategory = $selectedGroup->first();
                    
                    // Check if has both with_driver and without_driver
                    $hasWithDriver = $selectedGroup->where('with_driver', true)->count() > 0;
                    $hasWithoutDriver = $selectedGroup->where('with_driver', false)->count() > 0;
                    
                    if ($hasWithDriver && $hasWithoutDriver) {
                        // Default to without driver
                        $this->with_driver = false;
                        $selectedRentalCategory = $selectedGroup->where('with_driver', false)->first();
                    } elseif ($hasWithDriver) {
                        $this->with_driver = true;
                        $selectedRentalCategory = $selectedGroup->where('with_driver', true)->first();
                    } else {
                        $this->with_driver = false;
                        $selectedRentalCategory = $selectedGroup->where('with_driver', false)->first();
                    }
                    
                    $this->daily_price = $selectedRentalCategory->price;
                    $this->rental_category_name = $selectedRentalCategory->rentalCategory->name ?? null;
                } else {
                    // If provided rental_category_id not found, fallback to first
                    $this->setDefaultRentalCategory($rentalCategories);
                }
            } else {
                // No rental_category_id provided, use first available
                $this->setDefaultRentalCategory($rentalCategories);
            }
        } else {
            // Fallback to category price
            $category = $this->vehicle->category;
            if ($category->price && $category->price > 0) {
                $this->daily_price = $category->price;
                $this->with_driver = false;
            } elseif ($category->price_with_driver && $category->price_with_driver > 0) {
                $this->daily_price = $category->price_with_driver;
                $this->with_driver = true;
            } else {
                $this->daily_price = $this->vehicle->price_per_day ?? 0;
            }
        }
    }
    
    private function setDefaultRentalCategory($rentalCategories)
    {
        // Group by rental_category_id
        $grouped = $rentalCategories->groupBy('rental_category_id');
        $firstGroup = $grouped->first();
        $firstRentalCategory = $firstGroup->first();
        
        $this->rental_category_id = $firstRentalCategory->rental_category_id;
        
        // Check if has both with_driver and without_driver
        $hasWithDriver = $firstGroup->where('with_driver', true)->count() > 0;
        $hasWithoutDriver = $firstGroup->where('with_driver', false)->count() > 0;
        
        if ($hasWithDriver && $hasWithoutDriver) {
            // Default to without driver
            $this->with_driver = false;
            $selectedRentalCategory = $firstGroup->where('with_driver', false)->first();
        } elseif ($hasWithDriver) {
            $this->with_driver = true;
            $selectedRentalCategory = $firstGroup->where('with_driver', true)->first();
        } else {
            $this->with_driver = false;
            $selectedRentalCategory = $firstGroup->where('with_driver', false)->first();
        }
        
        $this->daily_price = $selectedRentalCategory->price;
        $this->rental_category_name = $selectedRentalCategory->rentalCategory->name ?? null;
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
        
        // Get daily price
        if ($this->rental_category_id && $this->vehicle) {
            $vehicleRentalCategory = $this->vehicle->rentalCategories
                ->where('rental_category_id', $this->rental_category_id)
                ->where('with_driver', $this->with_driver)
                ->first();
            
            if ($vehicleRentalCategory && $vehicleRentalCategory->price > 0) {
                $this->daily_price = $vehicleRentalCategory->price;
                $this->rental_category_name = $vehicleRentalCategory->rentalCategory->name ?? null;
            } else {
                // Fallback to category price
                $this->getCategoryPrice();
            }
        } else {
            $this->getCategoryPrice();
        }
        
        $this->total_price = $this->total_days * $this->daily_price;
    }
    
    public function getCategoryPrice()
    {
        if (!$this->vehicle) return;
        
        $category = $this->vehicle->category;
        $hasPrice = $category->price && $category->price > 0;
        $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
        
        if ($this->with_driver && $hasPriceWithDriver) {
            $this->daily_price = $category->price_with_driver;
        } elseif ($hasPrice) {
            $this->daily_price = $category->price;
        } else {
            $this->daily_price = $this->vehicle->price_per_day ?? 0;
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
            // Validate step 1
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
            
            $this->currentStep = 2;
        } elseif ($this->currentStep == 2) {
            // Validate step 2
            $this->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'required|string',
                'notes' => 'nullable|string',
            ]);
            
            $this->currentStep = 3;
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
            
            $conflictingBooking = Booking::where('vehicle_id', $this->vehicle_id)
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
            
            // Check unavailable dates
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
            
            // Create booking
            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'vehicle_id' => $this->vehicle_id,
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
                    'vehicle_id' => $this->vehicle_id,
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
                
                // Send notification email to admin
                Mail::to('admin@dsarana.com')->send(new NewBookingNotification($booking));
                Mail::to('customerservice@dsarana.com')->send(new NewBookingNotification($booking));
            } catch (\Exception $e) {
                Log::error('Failed to send booking emails: ' . $e->getMessage());
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
