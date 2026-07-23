<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\SettingsPage;
use Filament\Forms;
use Filament\Forms\Form;

class AppearanceSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Appearance';
    protected static ?string $title = 'Appearance Settings';

    protected static string $settings = Setting::class;

    public static function getNavigationSort(): int
    {
        return 10;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo & Favicon')
                    ->schema([
                        Forms\Components\FileUpload::make('settings.logo_light')
                            ->label('Light Logo')
                            ->image()
                            ->nullable(),
                        Forms\Components\FileUpload::make('settings.logo_dark')
                            ->label('Dark Logo')
                            ->image()
                            ->nullable(),
                        Forms\Components\FileUpload::make('settings.logo_mobile')
                            ->label('Mobile Logo')
                            ->image()
                            ->nullable(),
                        Forms\Components\FileUpload::make('settings.favicon')
                            ->label('Favicon')
                            ->image()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Brand Colors')
                    ->schema([
                        Forms\Components\ColorPicker::make('settings.primary_color')
                            ->label('Primary Color')
                            ->default('#059669'),
                        Forms\Components\ColorPicker::make('settings.secondary_color')
                            ->label('Secondary Color')
                            ->default('#047857'),
                        Forms\Components\ColorPicker::make('settings.accent_color')
                            ->label('Accent Color')
                            ->default('#f59e0b'),
                        Forms\Components\ColorPicker::make('settings.success_color')
                            ->label('Success Color')
                            ->default('#10b981'),
                        Forms\Components\ColorPicker::make('settings.warning_color')
                            ->label('Warning Color')
                            ->default('#f59e0b'),
                        Forms\Components\ColorPicker::make('settings.danger_color')
                            ->label('Danger Color')
                            ->default('#ef4444'),
                    ])->columns(3),

                Forms\Components\Section::make('Header Settings')
                    ->schema([
                        Forms\Components\Select::make('settings.header_style')
                            ->label('Header Style')
                            ->options([
                                'transparent' => 'Transparent over Hero',
                                'solid' => 'Solid Background',
                                'centered' => 'Centered Logo',
                            ]),
                        Forms\Components\Toggle::make('settings.header_sticky')
                            ->label('Sticky Header'),
                        Forms\Components\Toggle::make('settings.top_bar_enabled')
                            ->label('Enable Top Bar'),
                    ])->columns(2),

                Forms\Components\Section::make('Footer Settings')
                    ->schema([
                        Forms\Components\Select::make('settings.footer_style')
                            ->label('Footer Style')
                            ->options([
                                '4-column' => '4 Columns',
                                '3-column' => '3 Columns',
                                'minimal' => 'Minimal',
                            ]),
                    ]),

                Forms\Components\Section::make('Button & UI')
                    ->schema([
                        Forms\Components\Select::make('settings.button_style')
                            ->label('Button Style')
                            ->options([
                                'rounded' => 'Rounded',
                                'pill' => 'Pill',
                                'square' => 'Square',
                            ]),
                        Forms\Components\Select::make('settings.container_max_width')
                            ->label('Container Max Width')
                            ->options([
                                'max-w-7xl' => 'Extra Large',
                                'max-w-6xl' => 'Large',
                                'max-w-5xl' => 'Medium',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Floating Elements')
                    ->schema([
                        Forms\Components\Toggle::make('settings.back_to_top')
                            ->label('Back to Top Button'),
                        Forms\Components\Toggle::make('settings.preloader')
                            ->label('Page Preloader'),
                        Forms\Components\Toggle::make('settings.whatsapp_float')
                            ->label('WhatsApp Float Button'),
                        Forms\Components\TextInput::make('settings.whatsapp_message')
                            ->label('WhatsApp Pre-filled Message'),
                        Forms\Components\Toggle::make('settings.dark_mode_toggle')
                            ->label('Dark Mode Toggle'),
                    ])->columns(2),
            ]);
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
