<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification for an event
     */
    public static function send(string $event, array $recipient, array $variables = []): bool
    {
        $templates = NotificationTemplate::active()->forEvent($event)->get();

        if ($templates->isEmpty()) {
            Log::warning("No notification template found for event: {$event}");
            return false;
        }

        $success = true;

        foreach ($templates as $template) {
            $channels = $template->channels ?? ['email'];
            
            foreach ($channels as $channel) {
                $result = match ($channel) {
                    'email' => self::sendEmail($template, $recipient, $variables),
                    'sms' => self::sendSms($template, $recipient, $variables),
                    'whatsapp' => self::sendWhatsApp($template, $recipient, $variables),
                    default => false,
                };

                if (!$result) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Send email notification
     */
    private static function sendEmail(NotificationTemplate $template, array $recipient, array $variables): bool
    {
        try {
            if (empty($recipient['email'])) {
                Log::warning('No email address provided for notification');
                return false;
            }

            $rendered = $template->render($variables);

            // For now, log the email. In production, integrate with mail service
            // Mail::to($recipient['email'])->send(new GenericMail($rendered['subject'], $rendered['body']));
            
            Log::info('Email notification sent', [
                'to' => $recipient['email'],
                'subject' => $rendered['subject'],
                'event' => $template->event,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'error' => $e->getMessage(),
                'event' => $template->event,
            ]);
            return false;
        }
    }

    /**
     * Send SMS notification
     */
    private static function sendSms(NotificationTemplate $template, array $recipient, array $variables): bool
    {
        try {
            if (empty($recipient['phone'])) {
                Log::warning('No phone number provided for SMS notification');
                return false;
            }

            $rendered = $template->render($variables);

            // For now, log the SMS. In production, integrate with SMS provider (Twilio, Vonage, etc.)
            // SMS::send($recipient['phone'], $rendered['body']);
            
            Log::info('SMS notification queued', [
                'to' => $recipient['phone'],
                'message' => substr($rendered['body'], 0, 160),
                'event' => $template->event,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS notification', [
                'error' => $e->getMessage(),
                'event' => $template->event,
            ]);
            return false;
        }
    }

    /**
     * Send WhatsApp notification
     */
    private static function sendWhatsApp(NotificationTemplate $template, array $recipient, array $variables): bool
    {
        try {
            if (empty($recipient['phone'])) {
                Log::warning('No phone number provided for WhatsApp notification');
                return false;
            }

            $rendered = $template->render($variables);

            // For now, log the WhatsApp message. In production, integrate with WhatsApp Business API
            // WhatsApp::send($recipient['phone'], $rendered['body']);
            
            Log::info('WhatsApp notification queued', [
                'to' => $recipient['phone'],
                'message' => $rendered['body'],
                'event' => $template->event,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification', [
                'error' => $e->getMessage(),
                'event' => $template->event,
            ]);
            return false;
        }
    }

    /**
     * Notify booking status change
     */
    public static function notifyBookingStatus(string $status, array $bookingData): bool
    {
        $event = match ($status) {
            'pending' => NotificationTemplate::EVENT_BOOKING_CREATED,
            'confirmed' => NotificationTemplate::EVENT_BOOKING_CONFIRMED,
            'paid' => NotificationTemplate::EVENT_BOOKING_PAID,
            'cancelled' => NotificationTemplate::EVENT_BOOKING_CANCELLED,
            default => null,
        };

        if (!$event) {
            return false;
        }

        return self::send($event, [
            'email' => $bookingData['email'] ?? null,
            'phone' => $bookingData['phone'] ?? null,
        ], $bookingData);
    }

    /**
     * Notify cargo status change
     */
    public static function notifyCargoStatus(string $status, array $cargoData): bool
    {
        $event = match ($status) {
            'booked' => NotificationTemplate::EVENT_CARGO_BOOKED,
            'in_transit' => NotificationTemplate::EVENT_CARGO_IN_TRANSIT,
            'delivered' => NotificationTemplate::EVENT_CARGO_DELIVERED,
            default => null,
        };

        if (!$event) {
            return false;
        }

        return self::send($event, [
            'email' => $cargoData['sender_email'] ?? null,
            'phone' => $cargoData['sender_phone'] ?? null,
        ], $cargoData);
    }

    /**
     * Notify investor application status
     */
    public static function notifyInvestorStatus(string $status, array $applicationData): bool
    {
        $event = match ($status) {
            'submitted' => NotificationTemplate::EVENT_INVESTOR_APPLICATION,
            'approved' => NotificationTemplate::EVENT_INVESTOR_APPROVED,
            'rejected' => NotificationTemplate::EVENT_INVESTOR_REJECTED,
            default => null,
        };

        if (!$event) {
            return false;
        }

        return self::send($event, [
            'email' => $applicationData['email'] ?? null,
            'phone' => $applicationData['phone'] ?? null,
        ], $applicationData);
    }
}
