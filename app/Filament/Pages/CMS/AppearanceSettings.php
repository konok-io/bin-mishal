<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;

class AppearanceSettings extends Page
{
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Appearance';
    protected static ?string $title = 'Appearance Settings';
    protected static string $view = 'filament.pages.settings-form';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::first()?->settings ?? [];
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->schema([
            Schemas\Components\Section::make('Logo & Favicon')
                ->schema([
                    Schemas\Components\FileUpload::make('logo_light')
                        ->label('Light Logo')
                        ->image()
                        ->nullable(),
                    Schemas\Components\FileUpload::make('logo_dark')
                        ->label('Dark Logo')
                        ->image()
                        ->nullable(),
                    Schemas\Components\FileUpload::make('logo_mobile')
                        ->label('Mobile Logo')
                        ->image()
                        ->nullable(),
                    Schemas\Components\FileUpload::make('favicon')
                        ->label('Favicon')
                        ->image()
                        ->nullable(),
                ])->columns(2),

            Schemas\Components\Section::make('Brand Colors')
                ->schema([
                    Schemas\Components\ColorPicker::make('primary_color')
                        ->label('Primary Color')
                        ->default('#059669'),
                    Schemas\Components\ColorPicker::make('secondary_color')
                        ->label('Secondary Color')
                        ->default('#047857'),
                    Schemas\Components\ColorPicker::make('accent_color')
                        ->label('Accent Color')
                        ->default('#f59e0b'),
                    Schemas\Components\ColorPicker::make('success_color')
                        ->label('Success Color')
                        ->default('#10b981'),
                    Schemas\Components\ColorPicker::make('warning_color')
                        ->label('Warning Color')
                        ->default('#f59e0b'),
                    Schemas\Components\ColorPicker::make('danger_color')
                        ->label('Danger Color')
                        ->default('#ef4444'),
                ])->columns(3),

            Schemas\Components\Section::make('Header Settings')
                ->schema([
                    Schemas\Components\Select::make('header_style')
                        ->label('Header Style')
                        ->options([
                            'transparent' => 'Transparent over Hero',
                            'solid' => 'Solid Background',
                            'centered' => 'Centered Logo',
                        ]),
                    Schemas\Components\Toggle::make('header_sticky')
                        ->label('Sticky Header'),
                    Schemas\Components\Toggle::make('top_bar_enabled')
                        ->label('Enable Top Bar'),
                ])->columns(2),

            Schemas\Components\Section::make('Footer Settings')
                ->schema([
                    Schemas\Components\Select::make('footer_style')
                        ->label('Footer Style')
                        ->options([
                            '4-column' => '4 Columns',
                            '3-column' => '3 Columns',
                            'minimal' => 'Minimal',
                        ]),
                ]),

            Schemas\Components\Section::make('Button & UI')
                ->schema([
                    Schemas\Components\Select::make('button_style')
                        ->label('Button Style')
                        ->options([
                            'rounded' => 'Rounded',
                            'pill' => 'Pill',
                            'square' => 'Square',
                        ]),
                    Schemas\Components\Select::make('container_max_width')
                        ->label('Container Max Width')
                        ->options([
                            'max-w-7xl' => 'Extra Large',
                            'max-w-6xl' => 'Large',
                            'max-w-5xl' => 'Medium',
                        ]),
                ])->columns(2),

            Schemas\Components\Section::make('Floating Elements')
                ->schema([
                    Schemas\Components\Toggle::make('back_to_top')
                        ->label('Back to Top Button'),
                    Schemas\Components\Toggle::make('preloader')
                        ->label('Page Preloader'),
                    Schemas\Components\Toggle::make('whatsapp_float')
                        ->label('WhatsApp Float Button'),
                    Schemas\Components\TextInput::make('whatsapp_message')
                        ->label('WhatsApp Pre-filled Message'),
                    Schemas\Components\Toggle::make('dark_mode_toggle')
                        ->label('Dark Mode Toggle'),
                ])->columns(2),
        ]);
    }

    public function save(): void
    {
        $setting = Setting::firstOrCreate([]);
        $setting->settings = $this->form->getState();
        $setting->save();
        
        $this->notify('success', 'Settings saved successfully.');
    }
}
