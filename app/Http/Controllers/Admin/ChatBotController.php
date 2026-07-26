<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    /**
     * Display AI Chat Assistant settings.
     */
    public function index()
    {
        $settings = [
            'chat_enabled' => Setting::getValue('chat_enabled', false),
            'chat_position' => Setting::getValue('chat_position', 'right'),
            'chat_greeting' => Setting::getValue('chat_greeting', 'Hello! How can I help you today?'),
            'chat_offline_message' => Setting::getValue('chat_offline_message', 'We are currently offline. Please leave a message and we will get back to you.'),
            'chat_business_hours_start' => Setting::getValue('chat_business_hours_start', '09:00'),
            'chat_business_hours_end' => Setting::getValue('chat_business_hours_end', '18:00'),
            'chat_ai_provider' => Setting::getValue('chat_ai_provider', 'openai'),
            'chat_knowledge_base' => Setting::getValue('chat_knowledge_base', ''),
            'chat_lead_capture_enabled' => Setting::getValue('chat_lead_capture_enabled', true),
            'chat_human_handoff_enabled' => Setting::getValue('chat_human_handoff_enabled', true),
        ];

        return view('admin.chat-bot.index', compact('settings'));
    }

    /**
     * Update AI Chat Assistant settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'chat_enabled' => 'boolean',
            'chat_position' => 'in:left,right',
            'chat_greeting' => 'nullable|string|max:500',
            'chat_offline_message' => 'nullable|string|max:500',
            'chat_business_hours_start' => 'nullable|date_format:H:i',
            'chat_business_hours_end' => 'nullable|date_format:H:i',
            'chat_ai_provider' => 'in:openai,anthropic,none',
            'chat_knowledge_base' => 'nullable|string',
            'chat_lead_capture_enabled' => 'boolean',
            'chat_human_handoff_enabled' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->back()->with('success', 'AI Chat settings updated successfully.');
    }

    /**
     * Display conversation logs.
     */
    public function conversations(Request $request)
    {
        // Placeholder for conversation logs
        $conversations = collect([]); // Would load from database in full implementation
        $stats = [
            'total' => 0,
            'resolved' => 0,
            'escalated' => 0,
            'pending' => 0,
        ];

        return view('admin.chat-bot.conversations', compact('conversations', 'stats'));
    }

    /**
     * Display chat analytics.
     */
    public function analytics()
    {
        $analytics = [
            'total_conversations' => 0,
            'ai_resolved' => 0,
            'human_escalated' => 0,
            'abandoned' => 0,
            'avg_response_time' => '0s',
            'resolution_rate' => '0%',
        ];

        return view('admin.chat-bot.analytics', compact('analytics'));
    }

    /**
     * Display human handoff queue.
     */
    public function handoff()
    {
        $pending = collect([]); // Would load from database
        return view('admin.chat-bot.handoff', compact('pending'));
    }
}
