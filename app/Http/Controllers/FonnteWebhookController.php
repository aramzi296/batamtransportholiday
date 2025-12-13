<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FonnteWebhookController extends Controller
{
    protected FonnteService $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    /**
     * Handle incoming webhook from Fonnte
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            // Get raw JSON body (Fonnte sends JSON in request body)
            $rawBody = $request->getContent();
            $data = [];
            
            if (!empty($rawBody)) {
                $data = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Fallback to request->all() if JSON decode fails
                    $data = $request->all();
                }
            } else {
                // Fallback to request->all() if body is empty
                $data = $request->all();
            }

            // Log raw request for debugging
            Log::info('Fonnte webhook raw request', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'raw_body' => $rawBody,
                'decoded_data' => $data,
                'all_data' => $request->all(),
            ]);

            // Extract webhook data
            $device = $data['device'] ?? null;
            $sender = $data['sender'] ?? null;
            $message = $data['message'] ?? null;
            $text = $data['text'] ?? null; // button text
            $member = $data['member'] ?? null; // group member who send the message
            $name = $data['name'] ?? null;
            $location = $data['location'] ?? null;
            $pollname = $data['pollname'] ?? null;
            $choices = $data['choices'] ?? null;

            // Data below will only received by device with all feature package
            $url = $data['url'] ?? null;
            $filename = $data['filename'] ?? null;
            $extension = $data['extension'] ?? null;

            // Log incoming webhook for debugging
            Log::info('Fonnte webhook received', [
                'device' => $device,
                'sender' => $sender,
                'message' => $message,
                'text' => $text,
                'member' => $member,
                'name' => $name,
                'all_data' => $data,
            ]);

            // Validate required fields
            if (!$sender || !$message) {
                Log::warning('Fonnte webhook missing required fields', [
                    'sender' => $sender,
                    'message' => $message,
                    'all_data' => $data,
                ]);

                // Return 200 OK even if validation fails to prevent Fonnte from retrying
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: sender or message'
                ], 200);
            }

            // Process message and generate reply
            $reply = $this->processMessage($message);

            // If message doesn't match any criteria, don't send reply
            if ($reply === null) {
                Log::info('Fonnte message does not match criteria, no reply sent', [
                    'sender' => $sender,
                    'message' => $message,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Message received but no reply needed'
                ]);
            }

            Log::info('Fonnte reply prepared', [
                'sender' => $sender,
                'original_message' => $message,
                'reply' => $reply,
            ]);

            // Send reply via Fonnte
            $result = $this->fonnteService->sendMessage($sender, $reply);

            Log::info('Fonnte sendMessage result', [
                'sender' => $sender,
                'result' => $result,
            ]);

            if ($result['success']) {
                Log::info('Fonnte reply sent successfully', [
                    'sender' => $sender,
                    'message' => $message,
                    'response' => $result['response'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Reply sent successfully'
                ]);
            } else {
                Log::error('Failed to send Fonnte reply', [
                    'sender' => $sender,
                    'original_message' => $message,
                    'reply_data' => $reply,
                    'error' => $result['error'] ?? 'Unknown error',
                    'http_code' => $result['http_code'] ?? null,
                    'response' => $result['response'] ?? null,
                ]);

                // Return 200 OK even if send fails to prevent Fonnte from retrying
                // But log the error for debugging
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send reply',
                    'error' => $result['error'] ?? 'Unknown error',
                    'http_code' => $result['http_code'] ?? null,
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error('Fonnte webhook exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return 200 OK even on exception to prevent Fonnte from retrying
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing webhook',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Process incoming message and generate reply
     * Returns null if message doesn't match any criteria (no reply needed)
     *
     * @param string $message
     * @return array|null
     */
    private function processMessage(string $message): ?array
    {
        // Original code uses case-sensitive comparison without trim
        // But we'll use case-insensitive for better UX
        $messageLower = strtolower(trim($message));
        $messageOriginal = trim($message);

        if ($messageLower === 'test' || $messageOriginal === 'test') {
            return [
                'message' => 'working great!',
            ];
        } elseif ($messageLower === 'image' || $messageOriginal === 'image') {
            return [
                'message' => 'image message',
                'url' => 'https://filesamples.com/samples/image/jpg/sample_640%C3%97426.jpg',
            ];
        } elseif ($messageLower === 'audio' || $messageOriginal === 'audio') {
            return [
                'message' => 'audio message',
                'url' => 'https://filesamples.com/samples/audio/mp3/sample3.mp3',
                'filename' => 'music',
            ];
        } elseif ($messageLower === 'video' || $messageOriginal === 'video') {
            return [
                'message' => 'video message',
                'url' => 'https://filesamples.com/samples/video/mp4/sample_640x360.mp4',
            ];
        } elseif ($messageLower === 'file' || $messageOriginal === 'file') {
            return [
                'message' => 'file message',
                'url' => 'https://filesamples.com/samples/document/docx/sample3.docx',
                'filename' => 'document',
            ];
        } else {
            // Message doesn't match any criteria, return null (no reply)
            return null;
        }
    }

    /**
     * Test endpoint to verify Fonnte service configuration
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function test(Request $request): JsonResponse
    {
        try {
            $phone = $request->input('phone', '628117007201');
            $testMessage = $request->input('message', 'Test message from DSarana');

            $reply = [
                'message' => $testMessage,
            ];

            $result = $this->fonnteService->sendMessage($phone, $reply);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['success'] ? 'Test message sent successfully' : 'Failed to send test message',
                'data' => $result,
            ], $result['success'] ? 200 : 500);
        } catch (\Exception $e) {
            Log::error('Fonnte test exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

