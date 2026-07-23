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
    private bool $enabled;

    public function __construct()
    {
        $this->enabled = config('binmishal.whatsapp.enabled', false);
        $this->phoneId = config('binmishal.whatsapp.phone_number');
        $this->accessToken = config('binmishal.whatsapp.api_token');
        $this->apiUrl = 'https://graph.facebook.com/v18.0';
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
     * Handle incoming webhook
     */
    public function handleWebhook(array $payload): array
    {
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
     * Format phone number
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
