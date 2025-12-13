<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

class FonnteService
{
    private string $apiUrl;
    private string $token;

    public function __construct()
    {
        $this->apiUrl = config('services.fonnte.api_url', 'https://api.fonnte.com/send');
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Send message via Fonnte API
     *
     * @param string $target Phone number (with country code, e.g., 628117007201)
     * @param array $data Message data with keys: message, url (optional), filename (optional)
     * @return array Response from API
     */
    public function sendMessage(string $target, array $data): array
    {
        // Log configuration
        Log::info('FonnteService sendMessage called', [
            'api_url' => $this->apiUrl,
            'token_set' => !empty($this->token),
            'token_length' => strlen($this->token),
            'target' => $target,
            'data' => $data,
        ]);

        // Check if token is configured
        if (empty($this->token)) {
            Log::error('Fonnte token not configured');
            return [
                'success' => false,
                'error' => 'Fonnte token not configured. Please set FONNTE_TOKEN in .env file',
                'http_code' => 0
            ];
        }

        $curl = curl_init();

        $postFields = [
            'target' => $target,
            'message' => $data['message'] ?? '',
        ];

        // Add optional fields if provided
        if (isset($data['url'])) {
            $postFields['url'] = $data['url'];
        }
        if (isset($data['filename'])) {
            $postFields['filename'] = $data['filename'];
        }

        Log::info('Fonnte API request', [
            'url' => $this->apiUrl,
            'post_fields' => $postFields,
            'headers' => ["Authorization: {$this->token}"],
        ]);

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "Authorization: {$this->token}"
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        curl_close($curl);

        Log::info('Fonnte API response', [
            'http_code' => $httpCode,
            'response' => $response,
            'error' => $error,
            'response_length' => strlen($response ?? ''),
        ]);

        if ($error) {
            Log::error('Fonnte API Error', [
                'error' => $error,
                'target' => $target,
                'http_code' => $httpCode,
                'response' => $response,
            ]);

            return [
                'success' => false,
                'error' => $error,
                'http_code' => $httpCode,
                'response' => $response
            ];
        }

        $responseData = json_decode($response, true);

        $result = [
            'success' => $httpCode === 200,
            'http_code' => $httpCode,
            'response' => $responseData ?? $response
        ];

        if (!$result['success']) {
            Log::error('Fonnte API returned non-200 status', [
                'http_code' => $httpCode,
                'response' => $response,
                'decoded_response' => $responseData,
            ]);
        }

        return $result;
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

