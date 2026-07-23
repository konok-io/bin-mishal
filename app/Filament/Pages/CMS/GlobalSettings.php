<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\Setting;
use Filament\Pages\SettingsPage;
use Filament\Forms;
use Filament\Forms\Form;

class GlobalSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Global Settings';
    protected static ?string $title = 'Global Settings';

    protected static string $settings = Setting::class;

    public static function getNavigationSort(): int
    {
        return 5;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\Section::make('Site Identity')
                                    ->schema([
                                        Forms\Components\TextInput::make('app_name')
                                            ->label('Site Name (English)'),
                                        Forms\Components\TextInput::make('app_name_bn')
                                            ->label('Site Name (Bengali)'),
                                        Forms\Components\TextInput::make('app_name_ar')
                                            ->label('Site Name (Arabic)'),
                                        Forms\Components\TextInput::make('tagline')
                                            ->label('Tagline'),
                                    ])->columns(2),
                                Forms\Components\Section::make('Company Info')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_phone')
                                            ->label('Phone'),
                                        Forms\Components\TextInput::make('company_mobile')
                                            ->label('Mobile'),
                                        Forms\Components\TextInput::make('company_email')
                                            ->label('Email'),
                                        Forms\Components\TextInput::make('whatsapp_number')
                                            ->label('WhatsApp'),
                                        Forms\Components\Textarea::make('company_address')
                                            ->label('Address'),
                                    ]),
                                Forms\Components\Section::make('Business Registration')
                                    ->schema([
                                        Forms\Components\TextInput::make('cr_number')
                                            ->label('CR Number'),
                                        Forms\Components\TextInput::make('vat_number')
                                            ->label('VAT Number'),
                                        Forms\Components\TextInput::make('license_number')
                                            ->label('License Number'),
                                        Forms\Components\TextInput::make('established_year')
                                            ->label('Established Year'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Forms\Components\Section::make('Social Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url(),
                                        Forms\Components\TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url(),
                                        Forms\Components\TextInput::make('twitter_url')
                                            ->label('X (Twitter)')
                                            ->url(),
                                        Forms\Components\TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url(),
                                        Forms\Components\TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url(),
                                        Forms\Components\TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url(),
                                        Forms\Components\TextInput::make('telegram_url')
                                            ->label('Telegram')
                                            ->url(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO & Analytics')
                            ->schema([
                                Forms\Components\Section::make('Default Meta')
                                    ->schema([
                                        Forms\Components\TextInput::make('default_meta_title')
                                            ->label('Default Meta Title'),
                                        Forms\Components\Textarea::make('default_meta_description')
                                            ->label('Default Meta Description'),
                                        Forms\Components\TextInput::make('og_image')
                                            ->label('Default OG Image')
                                            ->url(),
                                    ]),
                                Forms\Components\Section::make('Analytics')
                                    ->schema([
                                        Forms\Components\TextInput::make('ga4_id')
                                            ->label('Google Analytics 4 ID'),
                                        Forms\Components\TextInput::make('gtm_id')
                                            ->label('Google Tag Manager ID'),
                                        Forms\Components\TextInput::make('meta_pixel_id')
                                            ->label('Meta Pixel ID'),
                                    ]),
                                Forms\Components\Section::make('Search Console')
                                    ->schema([
                                        Forms\Components\Textarea::make('google_verification')
                                            ->label('Google Verification Code'),
                                        Forms\Components\Textarea::make('robots_txt')
                                            ->label('Custom robots.txt'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Booking')
                            ->schema([
                                Forms\Components\Section::make('Currency & Tax')
                                    ->schema([
                                        Forms\Components\TextInput::make('currency')
                                            ->label('Currency Code')
                                            ->default('SAR'),
                                        Forms\Components\TextInput::make('currency_symbol')
                                            ->label('Currency Symbol'),
                                        Forms\Components\TextInput::make('tax_rate')
                                            ->label('Tax Rate (%)')
                                            ->numeric(),
                                        Forms\Components\TextInput::make('service_charge')
                                            ->label('Service Charge (%)')
                                            ->numeric(),
                                    ])->columns(2),
                                Forms\Components\Section::make('Booking Terms')
                                    ->schema([
                                        Forms\Components\TextInput::make('min_booking_amount')
                                            ->label('Minimum Booking Amount'),
                                        Forms\Components\TextInput::make('cancellation_policy')
                                            ->label('Cancellation Policy'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Email & SMS')
                            ->schema([
                                Forms\Components\Section::make('Email Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('mail_from_address')
                                            ->label('From Address'),
                                        Forms\Components\TextInput::make('mail_from_name')
                                            ->label('From Name'),
                                    ]),
                                Forms\Components\Section::make('SMS Gateway')
                                    ->schema([
                                        Forms\Components\TextInput::make('sms_gateway')
                                            ->label('SMS Provider'),
                                        Forms\Components\TextInput::make('sms_api_key')
                                            ->label('API Key')
                                            ->password(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Integrations')
                            ->schema([
                                Forms\Components\Section::make('WhatsApp')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp_api_token')
                                            ->label('WhatsApp Business API Token')
                                            ->password(),
                                        Forms\Components\TextInput::make('whatsapp_phone_id')
                                            ->label('Phone Number ID'),
                                    ]),
                                Forms\Components\Section::make('Payment Gateways')
                                    ->schema([
                                        Forms\Components\TextInput::make('stripe_key')
                                            ->label('Stripe Key')
                                            ->password(),
                                        Forms\Components\TextInput::make('stripe_secret')
                                            ->label('Stripe Secret')
                                            ->password(),
                                        Forms\Components\TextInput::make('tap_secret')
                                            ->label('Tap Secret')
                                            ->password(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Legal')
                            ->schema([
                                Forms\Components\Section::make('Legal Pages')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('privacy_policy')
                                            ->label('Privacy Policy (English)'),
                                        \Filament\Forms\Components\RichEditor::make('privacy_policy_bn')
                                            ->label('Privacy Policy (Bengali)'),
                                        \Filament\Forms\Components\RichEditor::make('privacy_policy_ar')
                                            ->label('Privacy Policy (Arabic)'),
                                    ]),
                                Forms\Components\Section::make('Terms & Refund')
                                    ->schema([
                                        \Filament\Forms\Components\RichEditor::make('terms_of_service')
                                            ->label('Terms of Service'),
                                        \Filament\Forms\Components\RichEditor::make('refund_policy')
                                            ->label('Refund Policy'),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Maintenance')
                            ->schema([
                                Forms\Components\Section::make('Maintenance Mode')
                                    ->schema([
                                        Forms\Components\Toggle::make('maintenance_mode')
                                            ->label('Enable Maintenance Mode'),
                                        Forms\Components\Textarea::make('maintenance_message')
                                            ->label('Maintenance Message'),
                                        Forms\Components\TextInput::make('allowed_ips')
                                            ->label('Allowed IPs (comma separated)'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
