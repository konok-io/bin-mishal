<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public NewsletterSubscriber $subscriber;
    public string $verifyUrl;
    public string $siteName;

    public function __construct(NewsletterSubscriber $subscriber, string $verifyUrl)
    {
        $this->subscriber = $subscriber;
        $this->verifyUrl = $verifyUrl;
        $this->siteName = config('app.name', 'Bin Mishal Travels');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Newsletter Subscription - ' . $this->siteName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-verification',
            with: [
                'subscriber' => $this->subscriber,
                'verifyUrl' => $this->verifyUrl,
                'siteName' => $this->siteName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
