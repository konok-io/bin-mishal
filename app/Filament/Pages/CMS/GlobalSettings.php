<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Schemas;
use Filament\Schemas\Schema;

class GlobalSettings extends Page
{
    protected static ?string $title = 'Global Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'app_name' => Setting::getValue('app_name'),
            'app_name_bn' => Setting::getValue('app_name_bn'),
            'app_name_ar' => Setting::getValue('app_name_ar'),
            'site_tagline' => Setting::getValue('site_tagline'),
            'site_description' => Setting::getValue('site_description'),
            'contact_email' => Setting::getValue('contact_email'),
            'contact_phone' => Setting::getValue('contact_phone'),
            'contact_whatsapp' => Setting::getValue('contact_whatsapp'),
            'contact_address' => Setting::getValue('contact_address'),
            'working_hours' => Setting::getValue('working_hours'),
            'google_maps_embed' => Setting::getValue('google_maps_embed'),
            'facebook_url' => Setting::getValue('facebook_url'),
            'twitter_url' => Setting::getValue('twitter_url'),
            'instagram_url' => Setting::getValue('instagram_url'),
            'youtube_url' => Setting::getValue('youtube_url'),
            'linkedin_url' => Setting::getValue('linkedin_url'),
            'tiktok_url' => Setting::getValue('tiktok_url'),
            'show_login_button' => Setting::getValue('show_login_button'),
            'show_register_button' => Setting::getValue('show_register_button'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Schemas\Components\Tabs::make('Settings')
                ->tabs([
                    Schemas\Components\Tabs\Tab::make('General')
                        ->schema([
                            Schemas\Components\Section::make('Site Identity')
                                ->schema([
                                    Schemas\Components\TextInput::make('app_name')
                                        ->label('Site Name (English)'),
                                    Schemas\Components\TextInput::make('app_name_bn')
                                        ->label('Site Name (বাংলা)'),
                                    Schemas\Components\TextInput::make('app_name_ar')
                                        ->label('Site Name (العربية)'),
                                    Schemas\Components\TextInput::make('site_tagline')
                                        ->label('Tagline'),
                                    Schemas\Components\Textarea::make('site_description')
                                        ->label('Site Description'),
                                ])->columns(2),
                            Schemas\Components\Section::make('Contact Info')
                                ->schema([
                                    Schemas\Components\TextInput::make('contact_email')
                                        ->label('Email'),
                                    Schemas\Components\TextInput::make('contact_phone')
                                        ->label('Phone'),
                                    Schemas\Components\TextInput::make('contact_whatsapp')
                                        ->label('WhatsApp'),
                                    Schemas\Components\TextInput::make('working_hours')
                                        ->label('Working Hours'),
                                    Schemas\Components\TextInput::make('contact_address')
                                        ->label('Address'),
                                    Schemas\Components\Textarea::make('google_maps_embed')
                                        ->label('Google Maps Embed Code'),
                                ])->columns(2),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Social Media')
                        ->schema([
                            Schemas\Components\TextInput::make('facebook_url')
                                ->label('Facebook')
                                ->url(),
                            Schemas\Components\TextInput::make('twitter_url')
                                ->label('Twitter'),
                            Schemas\Components\TextInput::make('instagram_url')
                                ->label('Instagram'),
                            Schemas\Components\TextInput::make('youtube_url')
                                ->label('YouTube'),
                            Schemas\Components\TextInput::make('linkedin_url')
                                ->label('LinkedIn'),
                            Schemas\Components\TextInput::make('tiktok_url')
                                ->label('TikTok'),
                        ])->columns(2),

                    Schemas\Components\Tabs\Tab::make('Header/Footer')
                        ->schema([
                            Schemas\Components\Toggle::make('show_login_button')
                                ->label('Show Login Button'),
                            Schemas\Components\Toggle::make('show_register_button')
                                ->label('Show Register Button'),
                            Schemas\Components\Textarea::make('footer_about_text')
                                ->label('Footer About Text'),
                            Schemas\Components\TextInput::make('copyright_text')
                                ->label('Copyright Text'),
                            Schemas\Components\Toggle::make('show_social_icons')
                                ->label('Show Social Icons in Footer'),
                            Schemas\Components\Toggle::make('show_newsletter')
                                ->label('Show Newsletter Form'),
                        ])->columns(2),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }
        
        Setting::clearCache();
        
        $this->notify('success', 'Settings saved successfully.');
    }
}
