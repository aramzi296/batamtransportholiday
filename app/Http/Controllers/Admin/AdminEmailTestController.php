<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class AdminEmailTestController extends Controller
{
    /**
     * Display the email test page
     */
    public function index()
    {
        // Get email configuration from .env
        $mailConfig = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        return view('admin.test-email', compact('mailConfig'));
    }

    /**
     * Send test email
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $to = $request->input('to');
            $subject = $request->input('subject');
            $message = $request->input('message');

            // Create a simple test email
            Mail::raw($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                     ->subject($subject)
                     ->from(
                         config('mail.from.address'),
                         config('mail.from.name')
                     );
            });

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim!',
                'data' => [
                    'to' => $to,
                    'subject' => $subject,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Email test failed: ' . $e->getMessage(), [
                'exception' => $e,
                'to' => $request->input('to'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

