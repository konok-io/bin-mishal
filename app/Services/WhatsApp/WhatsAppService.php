<?php

declare(strict_types=1);

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $phoneId;
    private string $accessToken;
    private string $apiUrl;
    private string $appSecret;
    private bool $enabled;

    public function __construct()
    {
        $this->enabled = config('binmishal.whatsapp.enabled', false);
        $this->phoneId = config('binmishal.whatsapp.phone_number_id');
        $this->accessToken = config('binmishal.whatsapp.access_token');
        $this->appSecret = config('binmishal.whatsapp.app_secret', '');
        $this->apiUrl = 'https://graph.facebook.com/v18.0';
    }

    /**
     * Verify webhook signature from Meta
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): array
    {
        if ($mode === 'subscribe' && $token === config('binmishal.whatsapp.verify_token')) {
            return ['success' => true, 'challenge' => $challenge];
        }

        return ['success' => false, 'error' => 'Invalid verification'];
    }

    /**
     * Verify incoming webhook signature
     * Meta sends X-Hub-Signature-256 header with HMAC SHA256
     */
    public function verifySignature(string $payload, ?string $signature): bool
    {
        if (!$this->appSecret || !$signature) {
            Log::warning('WhatsApp: Missing app secret or signature');
            return false;
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $this->appSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('WhatsApp: Invalid signature');
            return false;
        }

        return true;
    }

    /**
     * Send text message
     */
    public function sendMessage(string $to, string $message): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'error' => 'WhatsApp integration is disabled'];
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post("{$this->apiUrl}/{$this->phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhone($to),
                    'type' => 'text',
                    'text' => [
                        'preview_url' => false,
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response['messages'][0]['id'] ?? null,
                ];
            }

            Log::error('WhatsApp send failed', $response->json());
            return ['success' => false, 'error' => 'Send failed'];
        } catch (\Exception $e) {
            Log::error('WhatsApp error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send template message
     */
    public function sendTemplate(string $to, string $templateName, array $components = []): array
    {
        if (!$this->enabled) {
            return ['success' => false, 'error' => 'WhatsApp integration is disabled'];
        }

        try {
            $body = [
                'messaging_product' => 'whatsapp',
                'to' => $this->formatPhone($to),
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => 'en'],
                ],
            ];

            if (!empty($components)) {
                $body['template']['components'] = $components;
            }

            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post("{$this->apiUrl}/{$this->phoneId}/messages", $body);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response['messages'][0]['id'] ?? null,
                ];
            }

            return ['success' => false, 'error' => 'Template send failed'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send with variables
     */
    public function sendWithVariables(string $to, string $templateName, array $variables): array
    {
        $components = [
            [
                'type' => 'body',
                'parameters' => array_map(fn($value) => ['type' => 'text', 'text' => (string) $value], $variables),
            ],
        ];

        return $this->sendTemplate($to, $templateName, $components);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(string $messageId): bool
    {
        if (!$this->enabled) {
            return false;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(10)
                ->post("{$this->apiUrl}/{$this->phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'status' => 'read',
                    'message_id' => $messageId,
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Handle incoming webhook with signature verification
     */
    public function handleWebhook(array $payload, ?string $signature = null): array
    {
        // Verify signature if provided
        if ($signature !== null && !$this->verifySignature(json_encode($payload), $signature)) {
            Log::warning('WhatsApp: Rejected unsigned webhook');
            return ['type' => 'invalid', 'error' => 'Invalid signature'];
        }

        if (isset($payload['entry'][0]['changes'][0]['value']['messages'][0])) {
            $message = $payload['entry'][0]['changes'][0]['value']['messages'][0];

            return [
                'type' => 'incoming',
                'from' => $message['from'],
                'message_id' => $message['id'],
                'text' => $message['text']['body'] ?? null,
                'timestamp' => $message['timestamp'],
            ];
        }

        return ['type' => 'unknown'];
    }

    /**
     * Format phone number to WhatsApp format
     */
    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '966' . substr($phone, 1);
        }

        if (!str_starts_with($phone, '966')) {
            $phone = '966' . $phone;
        }

        return $phone;
    }
}
