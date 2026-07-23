<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $type = ucfirst($this->booking->booking_type);

        return (new MailMessage)
            ->subject("Booking Confirmed - {$this->booking->booking_no}")
            ->line("Your {$type} booking has been confirmed!")
            ->line('Booking Number: ' . $this->booking->booking_no)
            ->line('PNR: ' . ($this->booking->pnr ?? 'N/A'))
            ->line('Total Amount: SAR ' . number_format($this->booking->total_amount, 2))
            ->line('Paid Amount: SAR ' . number_format($this->booking->paid_amount, 2))
            ->action('View Booking', url('/portal/bookings/' . $this->booking->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_confirmation',
            'booking_id' => $this->booking->id,
            'booking_no' => $this->booking->booking_no,
            'booking_type' => $this->booking->booking_type,
        ];
    }
}
