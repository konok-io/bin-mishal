<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\Setting;
use Filament\Pages\SettingsPage;
use Filament\Schemas;
use Filament\Schemas\Schema;

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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Tabs::make('Settings')
                    ->tabs([
                        Schemas\Components\Tabs\Tab::make('General')
                            ->schema([
                                Schemas\Components\Section::make('Site Identity')
                                    ->schema([
                                        Schemas\Components\TextInput::make('app_name')
                                            ->label('Site Name (English)'),
                                        Schemas\Components\TextInput::make('app_name_bn')
                                            ->label('Site Name (Bengali)'),
                                        Schemas\Components\TextInput::make('app_name_ar')
                                            ->label('Site Name (Arabic)'),
                                        Schemas\Components\TextInput::make('tagline')
                                            ->label('Tagline'),
                                    ])->columns(2),
                                Schemas\Components\Section::make('Company Info')
                                    ->schema([
                                        Schemas\Components\TextInput::make('company_phone')
                                            ->label('Phone'),
                                        Schemas\Components\TextInput::make('company_mobile')
                                            ->label('Mobile'),
                                        Schemas\Components\TextInput::make('company_email')
                                            ->label('Email'),
                                        Schemas\Components\TextInput::make('whatsapp_number')
                                            ->label('WhatsApp'),
                                        Schemas\Components\Textarea::make('company_address')
                                            ->label('Address'),
                                    ]),
                                Schemas\Components\Section::make('Business Registration')
                                    ->schema([
                                        Schemas\Components\TextInput::make('cr_number')
                                            ->label('CR Number'),
                                        Schemas\Components\TextInput::make('vat_number')
                                            ->label('VAT Number'),
                                        Schemas\Components\TextInput::make('license_number')
                                            ->label('License Number'),
                                        Schemas\Components\TextInput::make('established_year')
                                            ->label('Established Year'),
                                    ])->columns(2),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Schemas\Components\Section::make('Social Links')
                                    ->schema([
                                        Schemas\Components\TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url(),
                                        Schemas\Components\TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url(),
                                        Schemas\Components\TextInput::make('twitter_url')
                                            ->label('X (Twitter)')
                                            ->url(),
                                        Schemas\Components\TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url(),
                                        Schemas\Components\TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url(),
                                        Schemas\Components\TextInput::make('tiktok_url')
                                            ->label('TikTok')
                                            ->url(),
                                        Schemas\Components\TextInput::make('telegram_url')
                                            ->label('Telegram')
                                            ->url(),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('SEO & Analytics')
                            ->schema([
                                Schemas\Components\Section::make('Default Meta')
                                    ->schema([
                                        Schemas\Components\TextInput::make('default_meta_title')
                                            ->label('Default Meta Title'),
                                        Schemas\Components\Textarea::make('default_meta_description')
                                            ->label('Default Meta Description'),
                                        Schemas\Components\TextInput::make('og_image')
                                            ->label('Default OG Image')
                                            ->url(),
                                    ]),
                                Schemas\Components\Section::make('Analytics')
                                    ->schema([
                                        Schemas\Components\TextInput::make('ga4_id')
                                            ->label('Google Analytics 4 ID'),
                                        Schemas\Components\TextInput::make('gtm_id')
                                            ->label('Google Tag Manager ID'),
                                        Schemas\Components\TextInput::make('meta_pixel_id')
                                            ->label('Meta Pixel ID'),
                                    ]),
                                Schemas\Components\Section::make('Search Console')
                                    ->schema([
                                        Schemas\Components\Textarea::make('google_verification')
                                            ->label('Google Verification Code'),
                                        Schemas\Components\Textarea::make('robots_txt')
                                            ->label('Custom robots.txt'),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Booking')
                            ->schema([
                                Schemas\Components\Section::make('Currency & Tax')
                                    ->schema([
                                        Schemas\Components\TextInput::make('currency')
                                            ->label('Currency Code')
                                            ->default('SAR'),
                                        Schemas\Components\TextInput::make('currency_symbol')
                                            ->label('Currency Symbol'),
                                        Schemas\Components\TextInput::make('tax_rate')
                                            ->label('Tax Rate (%)')
                                            ->numeric(),
                                        Schemas\Components\TextInput::make('service_charge')
                                            ->label('Service Charge (%)')
                                            ->numeric(),
                                    ])->columns(2),
                                Schemas\Components\Section::make('Booking Terms')
                                    ->schema([
                                        Schemas\Components\TextInput::make('min_booking_amount')
                                            ->label('Minimum Booking Amount'),
                                        Schemas\Components\TextInput::make('cancellation_policy')
                                            ->label('Cancellation Policy'),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Email & SMS')
                            ->schema([
                                Schemas\Components\Section::make('Email Settings')
                                    ->schema([
                                        Schemas\Components\TextInput::make('mail_from_address')
                                            ->label('From Address'),
                                        Schemas\Components\TextInput::make('mail_from_name')
                                            ->label('From Name'),
                                    ]),
                                Schemas\Components\Section::make('SMS Gateway')
                                    ->schema([
                                        Schemas\Components\TextInput::make('sms_gateway')
                                            ->label('SMS Provider'),
                                        Schemas\Components\TextInput::make('sms_api_key')
                                            ->label('API Key')
                                            ->password(),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Integrations')
                            ->schema([
                                Schemas\Components\Section::make('WhatsApp')
                                    ->schema([
                                        Schemas\Components\TextInput::make('whatsapp_api_token')
                                            ->label('WhatsApp Business API Token')
                                            ->password(),
                                        Schemas\Components\TextInput::make('whatsapp_phone_id')
                                            ->label('Phone Number ID'),
                                    ]),
                                Schemas\Components\Section::make('Payment Gateways')
                                    ->schema([
                                        Schemas\Components\TextInput::make('stripe_key')
                                            ->label('Stripe Key')
                                            ->password(),
                                        Schemas\Components\TextInput::make('stripe_secret')
                                            ->label('Stripe Secret')
                                            ->password(),
                                        Schemas\Components\TextInput::make('tap_secret')
                                            ->label('Tap Secret')
                                            ->password(),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Legal')
                            ->schema([
                                Schemas\Components\Section::make('Legal Pages')
                                    ->schema([
                                        \Filament\Schemas\Components\RichEditor::make('privacy_policy')
                                            ->label('Privacy Policy (English)'),
                                        \Filament\Schemas\Components\RichEditor::make('privacy_policy_bn')
                                            ->label('Privacy Policy (Bengali)'),
                                        \Filament\Schemas\Components\RichEditor::make('privacy_policy_ar')
                                            ->label('Privacy Policy (Arabic)'),
                                    ]),
                                Schemas\Components\Section::make('Terms & Refund')
                                    ->schema([
                                        \Filament\Schemas\Components\RichEditor::make('terms_of_service')
                                            ->label('Terms of Service'),
                                        \Filament\Schemas\Components\RichEditor::make('refund_policy')
                                            ->label('Refund Policy'),
                                    ]),
                            ]),

                        Schemas\Components\Tabs\Tab::make('Maintenance')
                            ->schema([
                                Schemas\Components\Section::make('Maintenance Mode')
                                    ->schema([
                                        Schemas\Components\Toggle::make('maintenance_mode')
                                            ->label('Enable Maintenance Mode'),
                                        Schemas\Components\Textarea::make('maintenance_message')
                                            ->label('Maintenance Message'),
                                        Schemas\Components\TextInput::make('allowed_ips')
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
