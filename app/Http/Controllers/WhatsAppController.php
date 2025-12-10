<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send WhatsApp message for booking
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendBookingMessage(Request $request): JsonResponse
    {
        $request->validate([
            'booking_code' => 'required|string|exists:bookings,booking_code',
            'phone_number' => 'nullable|string',
        ]);

        try {
            // Get booking with related data
            $booking = Booking::with([
                'vehicle.category', 
                'vehicle.brand', 
                'vehicle' => function($query) {
                    $query->with('brand');
                },
                'user'
            ])
                ->where('booking_code', $request->booking_code)
                ->firstOrFail();

            // Determine phone number
            $phoneNumber = $request->phone_number ?? $booking->customer_phone;
            
            if (empty($phoneNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon tidak ditemukan'
                ], 400);
            }

            // Format phone number
            $formattedPhone = $this->whatsappService->formatPhoneNumber($phoneNumber);

            // Generate message
            $message = $this->generateBookingMessage($booking);

            // Send WhatsApp message
            $result = $this->whatsappService->sendMessage($formattedPhone, $message);

            if ($result['success']) {
                Log::info('WhatsApp message sent successfully', [
                    'booking_code' => $booking->booking_code,
                    'phone' => $formattedPhone
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pesan WhatsApp berhasil dikirim',
                    'data' => [
                        'booking_code' => $booking->booking_code,
                        'phone' => $formattedPhone,
                        'response' => $result['response']
                    ]
                ]);
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'booking_code' => $booking->booking_code,
                    'phone' => $formattedPhone,
                    'error' => $result['error'] ?? 'Unknown error',
                    'http_code' => $result['http_code'] ?? null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesan WhatsApp',
                    'error' => $result['error'] ?? 'Unknown error'
                ], 500);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate booking message
     *
     * @param Booking $booking
     * @return string
     */
    private function generateBookingMessage(Booking $booking): string
    {
        $vehicle = $booking->vehicle;
        $category = $vehicle->category ?? null;
        
        // Get brand name
        $brand = '';
        if ($vehicle->brand_id && $vehicle->brand) {
            $brand = is_string($vehicle->brand) ? $vehicle->brand : ($vehicle->brand->name ?? '');
        } elseif ($vehicle->brand_name) {
            $brand = $vehicle->brand_name;
        } elseif (is_string($vehicle->brand)) {
            $brand = $vehicle->brand;
        }
        
        $message = "📋 *Konfirmasi Booking Kendaraan*\n\n";
        $message .= "Kode Booking: *{$booking->booking_code}*\n\n";
        
        $message .= "👤 *Data Penyewa:*\n";
        $message .= "Nama: {$booking->customer_name}\n";
        $message .= "Email: {$booking->customer_email}\n";
        $message .= "Telepon: {$booking->customer_phone}\n";
        $message .= "Alamat: {$booking->customer_address}\n\n";
        
        $message .= "🚗 *Detail Kendaraan:*\n";
        $message .= "Nama: {$vehicle->name}\n";
        $message .= "Merek: {$brand}\n";
        $message .= "Model: {$vehicle->model}\n";
        if ($category) {
            $message .= "Kategori: {$category->name}\n";
        }
        $message .= "Tahun: {$vehicle->year}\n";
        $message .= "Kursi: {$vehicle->seats} kursi\n";
        $message .= "Transmisi: {$vehicle->transmission}\n\n";
        
        $message .= "📅 *Detail Pemesanan:*\n";
        $message .= "Tanggal Mulai: " . $booking->start_date->format('d F Y') . "\n";
        $message .= "Tanggal Selesai: " . $booking->end_date->format('d F Y') . "\n";
        $message .= "Durasi: {$booking->total_days} hari\n";
        $message .= "Dengan Sopir: " . ($booking->with_driver ? 'Ya' : 'Tidak') . "\n\n";
        
        $message .= "💰 *Rincian Biaya:*\n";
        $message .= "Harga per Hari: Rp " . number_format($booking->daily_price, 0, ',', '.') . "\n";
        $message .= "Total Hari: {$booking->total_days} hari\n";
        $message .= "*Total Biaya: Rp " . number_format($booking->total_price, 0, ',', '.') . "*\n\n";
        
        $message .= "📊 *Status:* " . strtoupper($booking->status) . "\n\n";
        
        if ($booking->notes) {
            $message .= "📝 *Catatan:*\n{$booking->notes}\n\n";
        }
        
        $message .= "Terima kasih telah menggunakan layanan kami! 🙏\n";
        $message .= "Jika ada pertanyaan, silakan hubungi customer service kami.";

        return $message;
    }

    /**
     * Test WhatsApp API
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function test(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $phone = $this->whatsappService->formatPhoneNumber($request->phone);
        $message = $request->message ?? 'Halo, ini adalah pesan test dari sistem DSarana.';

        $result = $this->whatsappService->sendMessage($phone, $message);

        return response()->json($result);
    }
}

