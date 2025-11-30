<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
     * Send notification to admin
     */
    private function sendAdminNotification($data)
    {
        $adminEmail = config('mail.admin_email', 'admin@rentcarpro.com');
        
        $subject = 'Pesan Baru dari Website - ' . ucfirst($data['subject']);
        
        $message = "
        <h3>Pesan Baru dari Website</h3>
        <p><strong>Nama:</strong> {$data['name']}</p>
        <p><strong>Email:</strong> {$data['email']}</p>
        <p><strong>Telepon:</strong> {$data['phone']}</p>
        <p><strong>Subjek:</strong> " . $this->getSubjectLabel($data['subject']) . "</p>
        <p><strong>Newsletter:</strong> " . ($data['newsletter'] ? 'Ya' : 'Tidak') . "</p>
        <p><strong>Waktu:</strong> {$data['submitted_at']}</p>
        <hr>
        <p><strong>Pesan:</strong></p>
        <p>{$data['message']}</p>
        ";

        // Send email (using Laravel Mail - configure your mail settings)
        // Mail::html($message, function ($mail) use ($adminEmail, $subject, $data) {
        //     $mail->to($adminEmail)
        //          ->subject($subject)
        //          ->replyTo($data['email'], $data['name']);
        // });
    }

    /**
     * Send auto-reply to user
     */
    private function sendUserAutoReply($data)
    {
        $message = "
        <h3>Terima kasih telah menghubungi kami!</h3>
        <p>Halo {$data['name']},</p>
        <p>Pesan Anda telah kami terima dan akan segera kami proses. Tim customer service kami akan menghubungi Anda dalam waktu maksimal 24 jam.</p>
        <p><strong>Detail pesan Anda:</strong></p>
        <p>Subjek: " . $this->getSubjectLabel($data['subject']) . "</p>
        <p>Pesan: {$data['message']}</p>
        <hr>
        <p>Jika Anda membutuhkan bantuan segera, silakan hubungi kami di:</p>
        <p>📞 WhatsApp: +62 123 456 789</p>
        <p>📧 Email: info@rentcarpro.com</p>
        <p>🕐 Jam operasional: Senin-Jumat 08:00-20:00</p>
        <br>
        <p>Salam,<br>Tim Customer Service<br>D'Sarana</p>
        ";

        // Send email (using Laravel Mail - configure your mail settings)
        // Mail::html($message, function ($mail) use ($data) {
        //     $mail->to($data['email'])
        //          ->subject('Terima kasih telah menghubungi D\'Sarana');
        // });
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