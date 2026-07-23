<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\VisaApplication;
use App\Models\Document;
use App\Notifications\InvoicePaidNotification;
use App\Notifications\AppointmentReminderNotification;
use App\Notifications\VisaStatusNotification;
use App\Notifications\DocumentExpiryNotification;
use App\Notifications\BookingConfirmationNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send payment reminder
     */
    public function sendPaymentReminder(User $user, Invoice $invoice): void
    {
        $user->notify(new InvoicePaidNotification($invoice));
    }

    /**
     * Send appointment reminder
     */
    public function sendAppointmentReminder(User $user, Appointment $appointment): void
    {
        $user->notify(new AppointmentReminderNotification($appointment));
    }

    /**
     * Send visa status update
     */
    public function sendVisaStatusUpdate(User $user, VisaApplication $application, string $newStatus): void
    {
        $user->notify(new VisaStatusNotification($application, $newStatus));
    }

    /**
     * Send document expiry reminder
     */
    public function sendDocumentExpiryReminder(User $user, Document $document, int $daysRemaining): void
    {
        $user->notify(new DocumentExpiryNotification($document, $daysRemaining));
    }

    /**
     * Send booking confirmation
     */
    public function sendBookingConfirmation(User $user, Booking $booking): void
    {
        $user->notify(new BookingConfirmationNotification($booking));
    }

    /**
     * Send bulk notification
     */
    public function sendBulk(array $userIds, string $notificationClass, array $data = []): int
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $user->notify(new $notificationClass(...$data));
        }

        return count($users);
    }

    /**
     * Send WhatsApp message
     */
    public function sendWhatsApp(string $phone, string $message): bool
    {
        if (!config('binmishal.whatsapp.enabled')) {
            return false;
        }

        // WhatsApp API integration
        // $response = Http::post(config('binmishal.whatsapp.api_url'), [
        //     'phone' => $phone,
        //     'message' => $message,
        // ]);

        return true;
    }

    /**
     * Send SMS
     */
    public function sendSms(string $phone, string $message): bool
    {
        // SMS gateway integration
        // $response = Http::post('https://sms.gateway.com', [...]);

        return true;
    }
}
