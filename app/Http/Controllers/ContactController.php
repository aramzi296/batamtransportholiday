<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;
use App\Http\Controllers\WhatsAppController;

class ContactController extends Controller
{
    /**
     * Show the contact page
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|in:booking,pricing,vehicle,complaint,suggestion,other',
            'message' => 'required|string|max:2000',
            'newsletter' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali data yang Anda masukkan.');
        }

        try {
            // Store contact data (optional - you can save to database)
            $contactData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'newsletter' => $request->has('newsletter'),
                'submitted_at' => now(),
            ];

            // Send email notification to admin
            $this->sendAdminNotification($contactData);

            // Send WhatsApp notification to admin
            $this->sendAdminWhatsAppNotification($contactData);

            // Send auto-reply to user
            $this->sendUserAutoReply($contactData);

            return redirect()->route('contact')
                ->with('success', 'Pesan Anda telah berhasil dikirim! Tim kami akan menghubungi Anda dalam waktu 24 jam.');

        } catch (\Exception $e) {
            return redirect()->back()
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
        
        $subject = 'Pesan Baru dari Website - ' . $this->getSubjectLabel($data['subject']);
        
        $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .detail { margin: 10px 0; }
                .detail strong { display: inline-block; width: 120px; }
                .message-box { background-color: white; padding: 15px; border-left: 4px solid #007bff; margin-top: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Pesan Baru dari Website</h2>
                </div>
                <div class='content'>
                    <div class='detail'><strong>Nama:</strong> {$data['name']}</div>
                    <div class='detail'><strong>Email:</strong> <a href='mailto:{$data['email']}'>{$data['email']}</a></div>
                    <div class='detail'><strong>Telepon:</strong> " . ($data['phone'] ? "<a href='tel:{$data['phone']}'>{$data['phone']}</a>" : '-') . "</div>
                    <div class='detail'><strong>Subjek:</strong> " . $this->getSubjectLabel($data['subject']) . "</div>
                    <div class='detail'><strong>Newsletter:</strong> " . ($data['newsletter'] ? 'Ya' : 'Tidak') . "</div>
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
                Log::error('Failed to send contact email to admin: ' . $e->getMessage(), [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Send auto-reply to user
     */
    private function sendUserAutoReply($data)
    {
        $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { background-color: #f8f9fa; padding: 20px; }
                .contact-info { background-color: white; padding: 15px; margin-top: 15px; border-left: 4px solid #28a745; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Terima Kasih Telah Menghubungi Kami!</h2>
                </div>
                <div class='content'>
                    <p>Halo <strong>{$data['name']}</strong>,</p>
                    <p>Pesan Anda telah kami terima dan akan segera kami proses. Tim customer service kami akan menghubungi Anda dalam waktu maksimal 24 jam.</p>
                    <p><strong>Detail pesan Anda:</strong></p>
                    <ul>
                        <li><strong>Subjek:</strong> " . $this->getSubjectLabel($data['subject']) . "</li>
                        <li><strong>Pesan:</strong> " . nl2br(e($data['message'])) . "</li>
                    </ul>
                    <div class='contact-info'>
                        <p><strong>Jika Anda membutuhkan bantuan segera, silakan hubungi kami di:</strong></p>
                        <p>📞 Telepon: +62 821 7086 0825</p>
                        <p>📧 Email: admin@dsarana.com</p>
                        <p>🕐 Jam operasional: Senin-Minggu 08:00-20:00</p>
                    </div>
                    <p>Salam,<br><strong>Tim Customer Service<br>D'Sarana</strong></p>
                </div>
            </div>
        </body>
        </html>
        ";

        try {
            Mail::html($htmlMessage, function ($mail) use ($data) {
                $mail->from('admin@dsarana.com', 'D\'Sarana')
                     ->to($data['email'])
                     ->subject('Terima kasih telah menghubungi D\'Sarana');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send auto-reply email to user: ' . $e->getMessage());
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
                Log::warning('WhatsApp admin phone not configured for contact form');
                return;
            }

            // Generate WhatsApp message
            $message = $this->generateContactWhatsAppMessage($data);

            // Send to all admin numbers
            $whatsappService = new WhatsAppService();
            foreach ($phoneNumbers as $phone) {
                try {
                    $formattedPhone = $whatsappService->formatPhoneNumber(trim($phone));
                    $result = $whatsappService->sendMessage($formattedPhone, $message);

                    if ($result['success']) {
                        Log::info('WhatsApp contact notification sent successfully', [
                            'phone' => $formattedPhone,
                            'contact_name' => $data['name']
                        ]);
                    } else {
                        Log::error('Failed to send WhatsApp contact notification', [
                            'phone' => $formattedPhone,
                            'error' => $result['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('WhatsApp contact notification exception', [
                        'phone' => $phone,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp contact notification exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate WhatsApp message for contact form
     */
    private function generateContactWhatsAppMessage($data): string
    {
        $subjectLabel = $this->getSubjectLabel($data['subject']);
        
        $message = "📧 *PESAN BARU DARI WEBSITE*\n\n";
        $message .= "Subjek: *{$subjectLabel}*\n";
        $message .= "Waktu: " . $data['submitted_at']->format('d F Y H:i:s') . "\n\n";
        
        $message .= "👤 *Data Pengirim:*\n";
        $message .= "Nama: {$data['name']}\n";
        $message .= "Email: {$data['email']}\n";
        if ($data['phone']) {
            $message .= "Telepon: {$data['phone']}\n";
        }
        $message .= "Newsletter: " . ($data['newsletter'] ? 'Ya' : 'Tidak') . "\n\n";
        
        $message .= "💬 *Pesan:*\n";
        $message .= $data['message'] . "\n\n";
        
        $message .= "Silakan segera tanggapi pesan ini! ⚡";
        
        return $message;
    }

    /**
     * Get subject label
     */
    private function getSubjectLabel($subject)
    {
        $subjects = [
            'booking' => 'Pertanyaan Booking',
            'pricing' => 'Informasi Harga',
            'vehicle' => 'Informasi Kendaraan',
            'complaint' => 'Keluhan',
            'suggestion' => 'Saran',
            'other' => 'Lainnya',
        ];

        return $subjects[$subject] ?? 'Lainnya';
    }
}