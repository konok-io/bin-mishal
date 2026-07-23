<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Invoice $invoice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Reminder - Invoice ' . $this->invoice->invoice_no)
            ->line('This is a reminder that your invoice is overdue.')
            ->line('Invoice Number: ' . $this->invoice->invoice_no)
            ->line('Amount Due: SAR ' . number_format($this->invoice->balance, 2))
            ->line('Due Date: ' . $this->invoice->due_date->format('d M Y'))
            ->action('Pay Now', url('/payments/' . $this->invoice->id))
            ->line('Please complete your payment as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_reminder',
            'invoice_id' => $this->invoice->id,
            'invoice_no' => $this->invoice->invoice_no,
            'amount' => $this->invoice->balance,
        ];
    }
}
