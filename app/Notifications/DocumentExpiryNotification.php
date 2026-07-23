<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Document $document,
        public int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $urgency = $this->daysRemaining <= 30 ? 'URGENT: ' : '';

        return (new MailMessage)
            ->subject($urgency . 'Document Expiring Soon - ' . $this->document->document_type)
            ->line('Your document is expiring soon and needs attention.')
            ->line('Document Type: ' . ucfirst(str_replace('_', ' ', $this->document->document_type)))
            ->line('Expiry Date: ' . $this->document->expiry_date->format('d M Y'))
            ->line('Days Remaining: ' . $this->daysRemaining)
            ->action('View Document', url('/portal/documents/' . $this->document->id))
            ->line('Please renew your document before it expires.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'document_expiry',
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'expiry_date' => $this->document->expiry_date->toDateString(),
            'days_remaining' => $this->daysRemaining,
        ];
    }
}
