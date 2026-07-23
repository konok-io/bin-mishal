<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'whatsapp'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Appointment Reminder - Tomorrow')
            ->line('This is a reminder for your appointment tomorrow.')
            ->line('Date: ' . $this->appointment->preferred_date->format('d M Y'))
            ->line('Time: ' . $this->appointment->preferred_time)
            ->line('Service: ' . ucfirst($this->appointment->service_type))
            ->action('View Details', url('/appointments/' . $this->appointment->id));
    }

    public function toWhatsApp(object $notifiable): array
    {
        return [
            'message' => "Reminder: Your appointment is tomorrow.\n\n" .
                "Date: " . $this->appointment->preferred_date->format('d M Y') . "\n" .
                "Time: " . $this->appointment->preferred_time . "\n" .
                "Service: " . ucfirst($this->appointment->service_type),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appointment_reminder',
            'appointment_id' => $this->appointment->id,
            'date' => $this->appointment->preferred_date->toDateString(),
            'time' => $this->appointment->preferred_time,
        ];
    }
}
