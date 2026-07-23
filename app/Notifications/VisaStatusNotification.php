<?php

namespace App\Notifications;

use App\Models\VisaApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisaStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private array $statusMessages = [
        'submitted' => 'Your visa application has been submitted successfully.',
        'document_pending' => 'Additional documents are required for your visa application.',
        'under_review' => 'Your visa application is under review.',
        'government_processing' => 'Your visa is being processed by the government.',
        'approved' => 'Congratulations! Your visa has been approved.',
        'rejected' => 'Unfortunately, your visa application has been rejected.',
        'delivered' => 'Your visa has been delivered.',
    ];

    public function __construct(
        public VisaApplication $application,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'whatsapp'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->statusMessages[$this->newStatus] ?? 'Your visa status has been updated.';

        return (new MailMessage)
            ->subject('Visa Application Update - ' . $this->application->application_no)
            ->line($message)
            ->line('Application Number: ' . $this->application->application_no)
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->action('View Application', url('/portal/visas/' . $this->application->id));
    }

    public function toWhatsApp(object $notifiable): array
    {
        $message = $this->statusMessages[$this->newStatus] ?? 'Your visa status has been updated.';

        return [
            'message' => "Visa Update\n\n" .
                "Application: " . $this->application->application_no . "\n" .
                "Status: " . ucfirst(str_replace('_', ' ', $this->newStatus)) . "\n\n" .
                $message,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'visa_status',
            'application_id' => $this->application->id,
            'application_no' => $this->application->application_no,
            'status' => $this->newStatus,
        ];
    }
}
