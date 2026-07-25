<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\Setting;
use Filament\Pages\BasePage;
use Filament\Forms\Form;
use Filament\Schemas;
use Filament\Schemas\Schema;

class GlobalSettings extends BasePage
{
    protected static ?string $title = 'Global Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();
        $this->form->fill($setting?->toArray() ?? []);
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
                                    Schemas\Components\TextInput::make('tagline')
                                        ->label('Tagline'),
                                ])->columns(2),
                            Schemas\Components\Section::make('Company Info')
                                ->schema([
                                    Schemas\Components\TextInput::make('company_phone')
                                        ->label('Phone'),
                                    Schemas\Components\TextInput::make('company_email')
                                        ->label('Email'),
                                ]),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Social Media')
                        ->schema([
                            Schemas\Components\TextInput::make('facebook_url')
                                ->label('Facebook')
                                ->url(),
                            Schemas\Components\TextInput::make('youtube_url')
                                ->label('YouTube')
                                ->url(),
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
