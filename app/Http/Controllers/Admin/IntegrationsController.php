<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class IntegrationsController extends Controller
{
    /**
     * Display the integrations dashboard.
     */
    public function index()
    {
        // Payment Gateway (Moyasar)
        $paymentGateway = [
            'name' => 'Payment Gateway (Moyasar)',
            'description' => 'Saudi payment processor supporting Mada, Visa, Mastercard, Apple Pay',
            'configured' => !empty(env('MOYASAR_SECRET_KEY')),
            'env_key' => 'MOYASAR_SECRET_KEY',
            'documentation_url' => 'https://docs.moyasar.com/',
            'status' => $this->checkPaymentGateway(),
        ];

        // AI Chat Assistant
        $aiChat = [
            'name' => 'AI Chat Assistant',
            'description' => 'AI-powered chatbot for customer support',
            'configured' => !empty(env('OPENAI_API_KEY')) || !empty(env('ANTHROPIC_API_KEY')),
            'env_key' => 'OPENAI_API_KEY or ANTHROPIC_API_KEY',
            'documentation_url' => 'https://docs.anthropic.com/',
            'status' => $this->checkAIChat(),
        ];

        // WhatsApp Business API
        $whatsapp = [
            'name' => 'WhatsApp Business API',
            'description' => 'WhatsApp broadcast and messaging for customer outreach',
            'configured' => !empty(env('WHATSAPP_API_TOKEN')),
            'env_key' => 'WHATSAPP_API_TOKEN',
            'documentation_url' => 'https://developers.facebook.com/docs/whatsapp/',
            'status' => $this->checkWhatsApp(),
        ];

        // Bulk Email (Mailgun/SendGrid/SES)
        $email = [
            'name' => 'Bulk Email Service',
            'description' => 'Newsletter and broadcast email campaigns',
            'configured' => !empty(env('MAIL_MAILER')) && env('MAIL_MAILER') !== 'log',
            'env_key' => 'MAIL_MAILER',
            'documentation_url' => 'https://documentation.mailgun.com/',
            'status' => $this->checkEmailService(),
        ];

        // External Accounting
        $accounting = [
            'name' => 'External Accounting Software',
            'description' => 'Integration with QuickBooks, Zoho, or Tally',
            'configured' => false,
            'env_key' => 'ACCOUNTING_API_KEY',
            'documentation_url' => 'https://developer.quickbooks.com/',
            'status' => 'pending',
        ];

        // Biometric Device
        $biometric = [
            'name' => 'Biometric Device',
            'description' => 'Attendance tracking hardware integration (ZKTeco, Hikvision, eSSL)',
            'configured' => false,
            'env_key' => 'BIOMETRIC_API_ENDPOINT',
            'documentation_url' => 'https://www.zkteco.com/',
            'status' => 'pending',
        ];

        // Google Analytics / Tag Manager / AdSense
        $analytics = [
            'name' => 'Google Analytics 4',
            'description' => 'Website traffic and conversion tracking',
            'configured' => !empty(env('GA_MEASUREMENT_ID')),
            'env_key' => 'GA_MEASUREMENT_ID',
            'documentation_url' => 'https://developers.google.com/analytics',
            'status' => $this->checkAnalytics(),
        ];

        // ZATCA E-Invoicing
        $zatca = [
            'name' => 'ZATCA E-Invoicing (Fatoora)',
            'description' => 'Saudi Arabian e-invoicing compliance - RECOMMENDED',
            'configured' => false,
            'env_key' => 'ZATCA_API_KEY',
            'documentation_url' => 'https://zatca.gov.sa/',
            'status' => 'recommended',
        ];

        $integrations = [
            'payment' => $paymentGateway,
            'ai_chat' => $aiChat,
            'whatsapp' => $whatsapp,
            'email' => $email,
            'accounting' => $accounting,
            'biometric' => $biometric,
            'analytics' => $analytics,
            'zatca' => $zatca,
        ];

        return view('admin.integrations.index', compact('integrations'));
    }

    /**
     * Update integration settings.
     */
    public function update(Request $request)
    {
        // Note: For security, actual credentials should be set via .env file
        // This method just provides guidance on what to set

        return redirect()->back()->with(
            'info',
            'To configure integrations, please edit your .env file with the required API keys. ' .
            'Refer to the documentation links provided for each service.'
        );
    }

    /**
     * Check payment gateway status.
     */
    private function checkPaymentGateway()
    {
        if (empty(env('MOYASAR_SECRET_KEY'))) {
            return 'not_configured';
        }

        // Could add API connectivity check here
        return 'configured';
    }

    /**
     * Check AI chat status.
     */
    private function checkAIChat()
    {
        if (empty(env('OPENAI_API_KEY')) && empty(env('ANTHROPIC_API_KEY'))) {
            return 'not_configured';
        }
        return 'configured';
    }

    /**
     * Check WhatsApp status.
     */
    private function checkWhatsApp()
    {
        if (empty(env('WHATSAPP_API_TOKEN'))) {
            return 'not_configured';
        }
        return 'configured';
    }

    /**
     * Check email service status.
     */
    private function checkEmailService()
    {
        if (empty(env('MAIL_MAILER')) || env('MAIL_MAILER') === 'log') {
            return 'not_configured';
        }
        return 'configured';
    }

    /**
     * Check analytics status.
     */
    private function checkAnalytics()
    {
        if (empty(env('GA_MEASUREMENT_ID'))) {
            return 'not_configured';
        }
        return 'configured';
    }
}
