<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms;

class GlobalSettings extends Page
{
    protected static ?string $title = 'Global Settings';
    protected static ?string $navigationLabel = 'Global Settings';

    protected static ?string $navigationGroup = 'CMS';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-cog-6-tooth';
    }

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
        return $form->statePath('data')->schema([
            Tabs::make('Settings')
                ->tabs([
                    Tabs\Tab::make('General')
                        ->schema([
                            Section::make('Site Identity')
                                ->schema([
                                    TextInput::make('app_name')
                                        ->label('Site Name (English)'),
                                    TextInput::make('app_name_bn')
                                        ->label('Site Name (বাংলা)'),
                                    TextInput::make('app_name_ar')
                                        ->label('Site Name (العربية)'),
                                    TextInput::make('site_tagline')
                                        ->label('Tagline'),
                                    Textarea::make('site_description')
                                        ->label('Site Description'),
                                ])->columns(2),
                            Section::make('Contact Info')
                                ->schema([
                                    TextInput::make('contact_email')
                                        ->label('Email'),
                                    TextInput::make('contact_phone')
                                        ->label('Phone'),
                                    TextInput::make('contact_whatsapp')
                                        ->label('WhatsApp'),
                                    TextInput::make('working_hours')
                                        ->label('Working Hours'),
                                    TextInput::make('contact_address')
                                        ->label('Address'),
                                    Textarea::make('google_maps_embed')
                                        ->label('Google Maps Embed Code'),
                                ])->columns(2),
                        ]),

                    Tabs\Tab::make('Social Media')
                        ->schema([
                            TextInput::make('facebook_url')
                                ->label('Facebook')
                                ->url(),
                            TextInput::make('twitter_url')
                                ->label('Twitter'),
                            TextInput::make('instagram_url')
                                ->label('Instagram'),
                            TextInput::make('youtube_url')
                                ->label('YouTube'),
                            TextInput::make('linkedin_url')
                                ->label('LinkedIn'),
                            TextInput::make('tiktok_url')
                                ->label('TikTok'),
                        ])->columns(2),

                    Tabs\Tab::make('Header/Footer')
                        ->schema([
                            Toggle::make('show_login_button')
                                ->label('Show Login Button'),
                            Toggle::make('show_register_button')
                                ->label('Show Register Button'),
                            Textarea::make('footer_about_text')
                                ->label('Footer About Text'),
                            TextInput::make('copyright_text')
                                ->label('Copyright Text'),
                            Toggle::make('show_social_icons')
                                ->label('Show Social Icons in Footer'),
                            Toggle::make('show_newsletter')
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
