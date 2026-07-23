<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Handle newsletter subscription
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ], [
            'email.required' => __('Please enter your email address'),
            'email.email' => __('Please enter a valid email address'),
        ]);

        $email = strtolower(trim($request->email));
        $name = trim($request->name);

        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing && $existing->is_active && $existing->is_verified) {
            return response()->json([
                'success' => false,
                'message' => __('You are already subscribed to our newsletter!'),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $subscriber = NewsletterSubscriber::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name ?: $existing?->name,
                    'is_active' => true,
                    'is_verified' => false,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'subscribed_at' => now(),
                ]
            );

            // Generate new verification token
            $subscriber->verification_token = \Illuminate\Support\Str::random(64);
            $subscriber->save();

            DB::commit();

            // Send verification email
            $this->sendVerificationEmail($subscriber);

            return response()->json([
                'success' => true,
                'message' => __('Thank you for subscribing! Please check your email to verify your subscription.'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Newsletter subscription failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('Subscription failed. Please try again later.'),
            ], 500);
        }
    }

    /**
     * Verify subscription
     */
    public function verify(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            return view('frontend.newsletter.invalid')
                ->with('message', __('Invalid verification link.'));
        }

        if ($subscriber->is_verified) {
            return view('frontend.newsletter.already-verified')
                ->with('message', __('Your email is already verified.'));
        }

        $subscriber->update([
            'is_verified' => true,
            'verification_token' => null,
        ]);

        return view('frontend.newsletter.success')
            ->with('message', __('Thank you! Your subscription has been verified.'));
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(Request $request, string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return view('frontend.newsletter.invalid')
                ->with('message', __('Invalid unsubscribe link.'));
        }

        $subscriber->unsubscribe();

        return view('frontend.newsletter.unsubscribed')
            ->with('message', __('You have been unsubscribed from our newsletter.'));
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
}
