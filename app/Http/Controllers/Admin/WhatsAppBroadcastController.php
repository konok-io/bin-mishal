<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class WhatsAppBroadcastController extends Controller
{
    /**
     * Display WhatsApp Broadcast settings and history.
     */
    public function index()
    {
        $settings = [
            'whatsapp_enabled' => Setting::getValue('whatsapp_enabled', false),
            'whatsapp_number' => Setting::getValue('whatsapp_number', ''),
            'whatsapp_default_message' => Setting::getValue('whatsapp_default_message', 'Hello! I am interested in your services.'),
            'whatsapp_position' => Setting::getValue('whatsapp_position', 'left'),
            'whatsapp_button_color' => Setting::getValue('whatsapp_button_color', '#25D366'),
            'whatsapp_hide_pages' => Setting::getValue('whatsapp_hide_pages', ''),
        ];

        $broadcasts = collect([]); // Would load from database
        $recipients = [
            'users' => User::count(),
            'customers' => Customer::count(),
        ];

        return view('admin.whatsapp-broadcast.index', compact('settings', 'broadcasts', 'recipients'));
    }

    /**
     * Update WhatsApp floating button settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'whatsapp_enabled' => 'boolean',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_default_message' => 'nullable|string|max:500',
            'whatsapp_position' => 'in:left,right',
            'whatsapp_button_color' => 'nullable|string|max:20',
            'whatsapp_hide_pages' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'WhatsApp settings updated successfully.');
    }

    /**
     * Show compose broadcast form.
     */
    public function create()
    {
        $recipients = [
            'users' => User::where('status', 'active')->get(),
            'customers' => Customer::where('status', 'active')->get(),
        ];

        return view('admin.whatsapp-broadcast.create', compact('recipients'));
    }

    /**
     * Send broadcast message (placeholder - requires WhatsApp Business API).
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'recipients' => 'required|string|in:all_users,all_customers,all,selected',
            'selected_users' => 'nullable|array',
            'selected_customers' => 'nullable|array',
            'schedule' => 'nullable|date|after:now',
        ]);

        // Placeholder: Would integrate with WhatsApp Business API here
        // For now, just log the broadcast
        $broadcast = [
            'id' => uniqid(),
            'message' => $validated['message'],
            'recipients' => $validated['recipients'],
            'status' => 'pending',
            'created_at' => now(),
            'scheduled_at' => $validated['schedule'] ?? null,
        ];

        // In production: Send via WhatsApp Business API
        // For now: Show info that WhatsApp API is not configured

        return redirect()->route('admin.whatsapp-broadcast.index')
            ->with('info', 'WhatsApp Broadcast requires WhatsApp Business API credentials. Please configure in Integrations settings.');
    }

    /**
     * View broadcast details.
     */
    public function show($id)
    {
        $broadcast = [
            'id' => $id,
            'message' => 'Sample message',
            'status' => 'sent',
            'sent_count' => 0,
            'failed_count' => 0,
            'created_at' => now(),
        ];

        return view('admin.whatsapp-broadcast.show', compact('broadcast'));
    }
}
