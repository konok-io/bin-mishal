<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Submit contact form
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'phone_country_code' => 'nullable|string|max:10',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
            'type' => 'nullable|string|in:general,booking,cargo,visa,investor,complaint,feedback,testimonial,other',
        ], [
            'name.required' => __('validation.name_required'),
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'message.required' => __('validation.message_required'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please correct the errors below.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create contact message
        $message = ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'phone_country_code' => $request->input('phone_country_code', '+966'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'type' => $request->input('type', 'general'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification to admin
        NotificationService::send('contact_form_submitted', [
            'email' => $message->email,
        ], [
            'name' => $message->name,
            'email' => $message->email,
            'phone' => $message->phone,
            'subject' => $message->subject,
            'message' => $message->message,
            'type' => $message->type,
            'id' => $message->id,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('Thank you for your message! We will get back to you soon.'),
            ]);
        }

        return redirect()->back()
            ->with('success', __('Thank you for your message! We will get back to you soon.'));
    }
}
