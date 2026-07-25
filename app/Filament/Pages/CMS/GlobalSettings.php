<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\Setting;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;

class GlobalSettings extends Page
{
    protected static ?string $title = 'Global Settings';
    protected static string $view = 'filament.pages.settings-form';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();
        $this->form->fill($setting?->toArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->schema([
            Schemas\Components\Tabs::make('Settings')
                ->tabs([
                    Schemas\Components\Tabs\Tab::make('General')
                        ->schema([
                            Schemas\Components\Section::make('Site Identity')
                                ->schema([
                                    Schemas\Components\TextInput::make('app_name')
                                        ->label('Site Name (English)'),
                                    Schemas\Components\TextInput::make('tagline')
                                        ->label('Tagline'),
                                ])->columns(2),
                            Schemas\Components\Section::make('Company Info')
                                ->schema([
                                    Schemas\Components\TextInput::make('company_phone')
                                        ->label('Phone'),
                                    Schemas\Components\TextInput::make('company_email')
                                        ->label('Email'),
                                    Schemas\Components\Textarea::make('company_address')
                                        ->label('Address'),
                                ]),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Social Media')
                        ->schema([
                            Schemas\Components\Section::make('Social Links')
                                ->schema([
                                    Schemas\Components\TextInput::make('facebook_url')
                                        ->label('Facebook')
                                        ->url(),
                                    Schemas\Components\TextInput::make('youtube_url')
                                        ->label('YouTube')
                                        ->url(),
                                    Schemas\Components\TextInput::make('linkedin_url')
                                        ->label('LinkedIn')
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
                                ]),
                            Schemas\Components\Section::make('Analytics')
                                ->schema([
                                    Schemas\Components\TextInput::make('ga4_id')
                                        ->label('Google Analytics 4 ID'),
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
                                ]),
                        ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate([]);
        $setting->fill($this->form->getState())->save();
        
        $this->notify('success', 'Settings saved successfully.');
    }
}
