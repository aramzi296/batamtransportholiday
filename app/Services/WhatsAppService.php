<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $appKey;
    private $authKey;
    private $apiUrl;
    private $sandbox;

    public function __construct()
    {
        $this->appKey = config('services.whatsapp.appkey', '7d389aad-ba64-4330-bde9-79aac1c52b48');
        $this->authKey = config('services.whatsapp.authkey', 'lX0GKhWw3rCJBcErpWRpQZTfz5IszhomAMm5o8dxRZ6qMfcMh6');
        $this->apiUrl = config('services.whatsapp.api_url', 'https://app.saungwa.com/api/create-message');
        $this->sandbox = config('services.whatsapp.sandbox', 'false');
    }

    /**
     * Send WhatsApp message
     *
     * @param string $to Phone number (with country code, e.g., 628117007201)
     * @param string $message Message content
     * @return array Response from API
     */
    public function sendMessage(string $to, string $message): array
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'appkey' => $this->appKey,
                'authkey' => $this->authKey,
                'to' => $to,
                'message' => $message,
                'sandbox' => $this->sandbox
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        
        curl_close($curl);

        if ($error) {
            Log::error('WhatsApp API Error', [
                'error' => $error,
                'to' => $to,
                'http_code' => $httpCode
            ]);
            
            return [
                'success' => false,
                'error' => $error,
                'http_code' => $httpCode
            ];
        }

        $responseData = json_decode($response, true);
        
        return [
            'success' => $httpCode === 200,
            'http_code' => $httpCode,
            'response' => $responseData ?? $response
        ];
    }

    /**
     * Format phone number to WhatsApp format
     * Remove +, spaces, and dashes, ensure it starts with country code
     *
     * @param string $phoneNumber
     * @return string
     */
    public function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters except +
        $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
        
        // Remove + if present
        $phoneNumber = str_replace('+', '', $phoneNumber);
        
        // If starts with 0, replace with 62 (Indonesia country code)
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        }
        
        // If doesn't start with country code, assume it's Indonesian number
        if (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }
}

