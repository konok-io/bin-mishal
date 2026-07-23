<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterService
{
    /**
     * Subscribe a new email
     */
    public function subscribe(string $email, ?string $name = null, array $preferences = []): array
    {
        $email = strtolower(trim($email));
        
        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $email)->first();
        
        if ($existing && $existing->is_active && $existing->is_verified) {
            return [
                'success' => false,
                'message' => 'You are already subscribed to our newsletter!',
            ];
        }

        try {
            $subscriber = NewsletterSubscriber::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name ?: ($existing?->name),
                    'is_active' => true,
                    'is_verified' => false,
                    'verification_token' => Str::random(64),
                    'unsubscribe_token' => Str::random(64),
                    'subscribed_at' => now(),
                ]
            );

            // Send verification email
            $this->sendVerificationEmail($subscriber);

            return [
                'success' => true,
                'message' => 'Thank you for subscribing! Please check your email to verify your subscription.',
                'needs_verification' => true,
                'subscriber' => $subscriber,
            ];
        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Subscription failed. Please try again later.',
            ];
        }
    }

    /**
     * Verify subscription
     */
    public function verify(string $token): array
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return [
                'success' => false,
                'message' => 'Invalid verification link.',
            ];
        }

        if ($subscriber->is_verified) {
            return [
                'success' => true,
                'message' => 'Your email is already verified.',
            ];
        }

        $subscriber->update([
            'is_verified' => true,
            'verification_token' => null,
            'verified_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Thank you! Your subscription has been verified.',
        ];
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(string $token): array
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return [
                'success' => false,
                'message' => 'Invalid unsubscribe link.',
            ];
        }

        $subscriber->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'You have been unsubscribed from our newsletter.',
        ];
    }

    /**
     * Send verification email
     */
    protected function sendVerificationEmail(NewsletterSubscriber $subscriber): void
    {
        try {
            $verifyUrl = url("/newsletter/verify/{$subscriber->verification_token}");

            // Try to use notification template
            $template = NotificationTemplate::getTemplate('newsletter_verification');

            if ($template) {
                $body = str_replace([
                    '{{ subscriber_name }}',
                    '{{ verify_link }}',
                    '{{ verification_url }}',
                ], [
                    $subscriber->name ?: 'Subscriber',
                    $verifyUrl,
                    $verifyUrl,
                ], $template->body);

                Mail::to($subscriber->email)
                    ->send(new \App\Mail\GenericEmail($template->subject, $body));
            } else {
                // Fallback to simple email
                Mail::to($subscriber->email)->send(
                    new \App\Mail\NewsletterVerification($subscriber, $verifyUrl)
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
            // Don't fail the subscription if email fails
        }
    }

    /**
     * Get active subscribers
     */
    public function getActiveSubscribers(): \Illuminate\Database\Eloquent\Collection
    {
        return NewsletterSubscriber::where('is_active', true)
            ->where('is_verified', true)
            ->get();
    }

    /**
     * Get subscriber count
     */
    public function getSubscriberCount(): array
    {
        return [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'verified' => NewsletterSubscriber::where('is_verified', true)->count(),
            'subscribed' => NewsletterSubscriber::where('is_active', true)->where('is_verified', true)->count(),
        ];
    }

    /**
     * Check if email is subscribed
     */
    public function isSubscribed(string $email): bool
    {
        return NewsletterSubscriber::where('email', strtolower($email))
            ->where('is_active', true)
            ->where('is_verified', true)
            ->exists();
    }
}
