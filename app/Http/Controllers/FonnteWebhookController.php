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
            // Get webhook data
            $data = $request->all();

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
            ]);

            // Validate required fields
            if (!$sender || !$message) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required fields: sender or message'
                ], 400);
            }

            // Process message and generate reply
            $reply = $this->processMessage($message);

            // Send reply via Fonnte
            $result = $this->fonnteService->sendMessage($sender, $reply);

            if ($result['success']) {
                Log::info('Fonnte reply sent successfully', [
                    'sender' => $sender,
                    'message' => $message,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Reply sent successfully'
                ]);
            } else {
                Log::error('Failed to send Fonnte reply', [
                    'sender' => $sender,
                    'error' => $result['error'] ?? 'Unknown error',
                    'http_code' => $result['http_code'] ?? null
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send reply',
                    'error' => $result['error'] ?? 'Unknown error'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Fonnte webhook exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing webhook',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process incoming message and generate reply
     *
     * @param string $message
     * @return array
     */
    private function processMessage(string $message): array
    {
        $message = strtolower(trim($message));

        if ($message === 'test') {
            return [
                'message' => 'working great!',
            ];
        } elseif ($message === 'image') {
            return [
                'message' => 'image message',
                'url' => 'https://filesamples.com/samples/image/jpg/sample_640%C3%97426.jpg',
            ];
        } elseif ($message === 'audio') {
            return [
                'message' => 'audio message',
                'url' => 'https://filesamples.com/samples/audio/mp3/sample3.mp3',
                'filename' => 'music',
            ];
        } elseif ($message === 'video') {
            return [
                'message' => 'video message',
                'url' => 'https://filesamples.com/samples/video/mp4/sample_640x360.mp4',
            ];
        } elseif ($message === 'file') {
            return [
                'message' => 'file message',
                'url' => 'https://filesamples.com/samples/document/docx/sample3.docx',
                'filename' => 'document',
            ];
        } else {
            return [
                'message' => "Sorry, i don't understand. Please use one of the following keyword :

Test
Audio
Video
Image
File",
            ];
        }
    }
}

