<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['category', 'vehicleImages'])
            ->where('is_available', true)
            ->whereNotNull('queue_number');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by date availability
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $query->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'cancelled')
                  ->where(function ($query) use ($startDate, $endDate) {
                      $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<=', $startDate)
                                  ->where('end_date', '>=', $endDate);
                            });
                  });
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
        }

        // Sort by price
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('price_per_day', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('price_per_day', 'desc');
            } elseif ($request->sort === 'name') {
                $query->orderBy('name', 'asc');
            }
        } else {
            // Default: order by queue_number (ascending)
            $query->orderBy('queue_number', 'asc');
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // Get vehicles
        $vehicles = $query->get();
        
        // Expand vehicles: if category has both prices, duplicate the vehicle
        $expandedVehicles = collect();
        foreach ($vehicles as $vehicle) {
            $category = $vehicle->category;
            
            // Check if category has both prices
            $hasPrice = $category->price && $category->price > 0;
            $hasPriceWithDriver = $category->price_with_driver && $category->price_with_driver > 0;
            
            if ($hasPrice && $hasPriceWithDriver) {
                // Add vehicle twice: once for price, once for price_with_driver
                // First: without driver (should appear first)
                $vehicleWithoutDriver = clone $vehicle;
                $vehicleWithoutDriver->display_price = $category->price;
                $vehicleWithoutDriver->price_type = 'without_driver';
                $vehicleWithoutDriver->sort_order = $vehicle->queue_number * 10; // Multiply by 10 to allow insertion
                $expandedVehicles->push($vehicleWithoutDriver);
                
                // Second: with driver
                $vehicleWithDriver = clone $vehicle;
                $vehicleWithDriver->display_price = $category->price_with_driver;
                $vehicleWithDriver->price_type = 'with_driver';
                $vehicleWithDriver->sort_order = $vehicle->queue_number * 10 + 1; // +1 to appear after without_driver
                $expandedVehicles->push($vehicleWithDriver);
            } elseif ($hasPrice) {
                // Only price without driver
                $vehicle->display_price = $category->price;
                $vehicle->price_type = 'without_driver';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            } elseif ($hasPriceWithDriver) {
                // Only price with driver
                $vehicle->display_price = $category->price_with_driver;
                $vehicle->price_type = 'with_driver';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            } else {
                // Use vehicle's own price_per_day if category has no price
                $vehicle->display_price = $vehicle->price_per_day;
                $vehicle->price_type = 'default';
                $vehicle->sort_order = $vehicle->queue_number * 10;
                $expandedVehicles->push($vehicle);
            }
        }
        
        // Filter by driver type (with_driver or without_driver)
        if ($request->filled('driver_type')) {
            if ($request->driver_type === 'with_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && $v->price_type === 'with_driver';
                });
            } elseif ($request->driver_type === 'without_driver') {
                $expandedVehicles = $expandedVehicles->filter(function($v) {
                    return isset($v->price_type) && ($v->price_type === 'without_driver' || $v->price_type === 'default');
                });
            }
        }
        
        // Filter by price range
        if ($request->filled('price_min')) {
            $expandedVehicles = $expandedVehicles->filter(function($v) use ($request) {
                return ($v->display_price ?? $v->price_per_day) >= $request->price_min;
            });
        }
        if ($request->filled('price_max')) {
            $expandedVehicles = $expandedVehicles->filter(function($v) use ($request) {
                return ($v->display_price ?? $v->price_per_day) <= $request->price_max;
            });
        }
        
        // Sort expanded vehicles by sort_order (queue_number * 10 + offset)
        $expandedVehicles = $expandedVehicles->sortBy('sort_order')->values();
        
        // Paginate manually
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $items = $expandedVehicles->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $expandedVehicles->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        $categories = VehicleCategory::all();
        $brands = VehicleBrand::active()->orderBy('name')->get();
        
        // Get unique models for filter
        $models = Vehicle::where('is_available', true)
            ->whereNotNull('queue_number')
            ->distinct()
            ->orderBy('model')
            ->pluck('model')
            ->filter()
            ->values();

        return view('vehicles.index', [
            'vehicles' => $paginated, 
            'categories' => $categories,
            'brands' => $brands,
            'models' => $models
        ]);
    }

    public function show($slug)
    {
        $vehicle = Vehicle::with(['category', 'vehicleImages'])->where('slug', $slug)->firstOrFail();
        
        // Get related vehicles (same category)
        $relatedVehicles = Vehicle::with(['category', 'vehicleImages'])
            ->where('category_id', $vehicle->category_id)
            ->where('id', '!=', $vehicle->id)
            ->where('is_available', true)
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'asc')
            ->limit(3)
            ->get();

        return view('vehicles.show', compact('vehicle', 'relatedVehicles'));
    }
    
    public function checkAvailability(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $vehicle = Vehicle::findOrFail($id);
        $unavailableDates = $vehicle->getUnavailableDatesInRange($request->start_date, $request->end_date);
        
        return response()->json([
            'available' => empty($unavailableDates),
            'unavailable_dates' => $unavailableDates,
            'message' => empty($unavailableDates) 
                ? 'Kendaraan tersedia untuk tanggal yang dipilih' 
                : 'Kendaraan tidak tersedia pada beberapa tanggal'
        ]);
    }
    
    public function contact(Request $request, $slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->firstOrFail();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('vehicles.show', $slug)
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'message' => $request->message,
                'vehicle_id' => $vehicle->id,
                'vehicle_name' => $vehicle->name,
                'submitted_at' => now(),
            ];

            // Send email notification to admin
            $this->sendAdminNotification($contactData);

            // Send WhatsApp notification to admin
            $this->sendAdminWhatsAppNotification($contactData);

            return redirect()->route('vehicles.show', $slug)
                ->with('success', 'Pesan Anda telah berhasil dikirim! Tim kami akan menghubungi Anda dalam waktu 24 jam.');

        } catch (\Exception $e) {
            Log::error('Vehicle contact form error: ' . $e->getMessage());
            return redirect()->route('vehicles.show', $slug)
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi atau hubungi kami langsung.');
        }
    }
    
    /**
     * Send notification to admin via email
     */
    private function sendAdminNotification($data)
    {
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
        
        $subject = 'Pesan Baru tentang Kendaraan: ' . $data['vehicle_name'];
        
        $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #667eea; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .detail { margin: 10px 0; }
                .detail strong { display: inline-block; width: 150px; }
                .message-box { background-color: white; padding: 15px; border-left: 4px solid #667eea; margin-top: 15px; }
                .vehicle-info { background-color: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Pesan Baru tentang Kendaraan</h2>
                </div>
                <div class='content'>
                    <div class='vehicle-info'>
                        <strong>Kendaraan:</strong> {$data['vehicle_name']}<br>
                        <strong>ID Kendaraan:</strong> {$data['vehicle_id']}
                    </div>
                    <div class='detail'><strong>Nama:</strong> {$data['name']}</div>
                    <div class='detail'><strong>Email:</strong> <a href='mailto:{$data['email']}'>{$data['email']}</a></div>
                    <div class='detail'><strong>WhatsApp:</strong> <a href='https://wa.me/{$data['whatsapp']}'>{$data['whatsapp']}</a></div>
                    <div class='detail'><strong>Waktu:</strong> {$data['submitted_at']}</div>
                    <div class='message-box'>
                        <strong>Pesan:</strong>
                        <p>" . nl2br(e($data['message'])) . "</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";

        // Send to all admin emails
        foreach ($emailList as $email) {
            try {
                Mail::html($htmlMessage, function ($mail) use ($email, $subject, $data) {
                    $mail->from('admin@dsarana.com', 'D\'Sarana')
                         ->to($email)
                         ->subject($subject)
                         ->replyTo($data['email'], $data['name']);
                });
            } catch (\Exception $e) {
                Log::error('Failed to send vehicle contact email to admin: ' . $e->getMessage(), [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
    
    /**
     * Send WhatsApp notification to admin
     */
    private function sendAdminWhatsAppNotification($data)
    {
        try {
            // Get admin phone numbers
            $adminPhones = config('services.whatsapp.admin_phones', []);
            $adminPhone = config('services.whatsapp.admin_phone');
            
            // Combine both: use admin_phones if available, fallback to admin_phone
            $phoneNumbers = [];
            if (!empty($adminPhones) && is_array($adminPhones)) {
                $phoneNumbers = $adminPhones;
            }
            
            // Add single admin_phone if set and not already in array
            if (!empty($adminPhone) && !in_array($adminPhone, $phoneNumbers)) {
                $phoneNumbers[] = $adminPhone;
            }
            
            if (empty($phoneNumbers)) {
                Log::warning('WhatsApp admin phone not configured for vehicle contact form');
                return;
            }

            // Generate WhatsApp message
            $message = "🚗 *PESAN BARU TENTANG KENDARAAN*\n\n";
            $message .= "Kendaraan: *{$data['vehicle_name']}*\n";
            $message .= "ID: {$data['vehicle_id']}\n";
            $message .= "Waktu: " . $data['submitted_at']->format('d F Y H:i:s') . "\n\n";
            
            $message .= "👤 *Data Pengirim:*\n";
            $message .= "Nama: {$data['name']}\n";
            $message .= "Email: {$data['email']}\n";
            $message .= "WhatsApp: {$data['whatsapp']}\n\n";
            
            $message .= "💬 *Pesan:*\n";
            $message .= $data['message'] . "\n\n";
            
            $message .= "Silakan segera tanggapi pesan ini! ⚡";

            // Send to all admin numbers
            $whatsappService = new WhatsAppService();
            foreach ($phoneNumbers as $phone) {
                try {
                    $formattedPhone = $whatsappService->formatPhoneNumber(trim($phone));
                    $result = $whatsappService->sendMessage($formattedPhone, $message);

                    if ($result['success']) {
                        Log::info('WhatsApp vehicle contact notification sent successfully', [
                            'phone' => $formattedPhone,
                            'contact_name' => $data['name'],
                            'vehicle' => $data['vehicle_name']
                        ]);
                    } else {
                        Log::error('Failed to send WhatsApp vehicle contact notification', [
                            'phone' => $formattedPhone,
                            'error' => $result['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('WhatsApp vehicle contact notification exception', [
                        'phone' => $phone,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp vehicle contact notification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}