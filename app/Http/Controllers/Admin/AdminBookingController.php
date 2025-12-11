<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingEvent;
use App\Mail\BookingConfirmation;
use App\Services\WhatsAppService;
use App\Http\Controllers\WhatsAppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
        // Load vehicle images to allow main_image accessor resolve URLs
        $booking->load([
            'vehicle',
            'vehicle.vehicleImages',
            'vehicle.category',
            'vehicle.brand',
            'user',
            'events.user'
        ]);
        return view('admin.bookings.show', compact('booking'));
    }
    
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string',
            'daily_price' => 'nullable|numeric|min:0',
        ]);
        
        $oldStatus = $booking->status;
        
        $updateData = [
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'confirmed_at' => $request->status === 'confirmed' && !$booking->confirmed_at ? now() : $booking->confirmed_at,
        ];

        // Update harga per hari jika diisi, sekaligus total price berdasar total_days
        if ($request->filled('daily_price')) {
            $updateData['daily_price'] = $request->daily_price;
            $updateData['total_price'] = $booking->total_days * $request->daily_price;
        }

        $booking->update($updateData);
        
        // Log status change event
        if ($oldStatus !== $request->status) {
            $booking->logEvent(
                BookingEvent::TYPE_STATUS_CHANGED,
                "Status booking diubah dari {$oldStatus} menjadi {$request->status}",
                [
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                ],
                auth()->id()
            );
        }
        
        // If status changed to confirmed, update vehicle queue_number
        if ($oldStatus !== 'confirmed' && $request->status === 'confirmed') {
            $vehicle = $booking->vehicle;
            $lastQueueNumber = \App\Models\Vehicle::whereNotNull('queue_number')
                ->max('queue_number') ?? 0;
            
            $vehicle->update([
                'queue_number' => $lastQueueNumber + 1
            ]);
        }
        
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Status booking berhasil diperbarui!');
    }
    
    public function confirm(Booking $booking)
    {
        $oldStatus = $booking->status;
        $booking->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);
        
        // Log status change event
        $booking->logEvent(
            BookingEvent::TYPE_STATUS_CHANGED,
            "Status booking diubah dari {$oldStatus} menjadi confirmed",
            [
                'old_status' => $oldStatus,
                'new_status' => 'confirmed',
            ],
            auth()->id()
        );
        
        // Update vehicle queue_number: set to one number below the last queue number
        $vehicle = $booking->vehicle;
        $lastQueueNumber = \App\Models\Vehicle::whereNotNull('queue_number')
            ->max('queue_number') ?? 0;
        
        $vehicle->update([
            'queue_number' => $lastQueueNumber + 1
        ]);
        
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dikonfirmasi!');
    }
    
    public function cancel(Booking $booking)
    {
        $oldStatus = $booking->status;
        $booking->update(['status' => 'cancelled']);
        
        // Log status change event
        $booking->logEvent(
            BookingEvent::TYPE_STATUS_CHANGED,
            "Status booking diubah dari {$oldStatus} menjadi cancelled",
            [
                'old_status' => $oldStatus,
                'new_status' => 'cancelled',
            ],
            auth()->id()
        );
        
        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Booking berhasil dibatalkan!');
    }
    
    /**
     * Send confirmation email to customer manually
     */
    public function sendConfirmationEmail(Booking $booking)
    {
        try {
            Mail::to($booking->customer_email)->send(new BookingConfirmation($booking));
            
            // Log email confirmation sent event
            $booking->logEvent(
                BookingEvent::TYPE_EMAIL_CONFIRMATION_SENT,
                'Email konfirmasi dikirim ke customer oleh admin',
                ['email' => $booking->customer_email],
                auth()->id()
            );
            
            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', 'Email konfirmasi berhasil dikirim ke customer!');
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email: ' . $e->getMessage());
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
    
    /**
     * Send WhatsApp confirmation to customer manually
     */
    public function sendConfirmationWhatsApp(Booking $booking)
    {
        try {
            $whatsappController = new WhatsAppController(new WhatsAppService());
            $result = $whatsappController->sendBookingMessage(
                new \Illuminate\Http\Request([
                    'booking_code' => $booking->booking_code,
                    'phone_number' => $booking->customer_phone,
                ])
            );
            
            if ($result->getData()->success ?? false) {
                // Log WhatsApp confirmation sent event
                $booking->logEvent(
                    BookingEvent::TYPE_WHATSAPP_CONFIRMATION_SENT,
                    'WhatsApp konfirmasi dikirim ke customer oleh admin',
                    [
                        'phone' => $booking->customer_phone,
                    ],
                    auth()->id()
                );
                
                return redirect()->route('admin.bookings.show', $booking)
                    ->with('success', 'WhatsApp konfirmasi berhasil dikirim ke customer!');
            } else {
                return redirect()->route('admin.bookings.show', $booking)
                    ->with('error', 'Gagal mengirim WhatsApp: ' . ($result->getData()->message ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp confirmation: ' . $e->getMessage());
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Gagal mengirim WhatsApp: ' . $e->getMessage());
        }
    }
}