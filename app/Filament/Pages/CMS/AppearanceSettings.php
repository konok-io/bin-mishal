<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Page;

class AppearanceSettings extends Page
{
    protected static ?string $title = 'Appearance Settings';
    protected static ?string $navigationLabel = 'Appearance';
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationGroup = 'Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'logo_light' => Setting::getValue('logo_light'),
            'logo_dark' => Setting::getValue('logo_dark'),
            'logo_mobile' => Setting::getValue('logo_mobile'),
            'favicon' => Setting::getValue('favicon'),
            'og_image' => Setting::getValue('og_image'),
            'primary_color' => Setting::getValue('primary_color'),
            'secondary_color' => Setting::getValue('secondary_color'),
            'accent_color' => Setting::getValue('accent_color'),
            'header_cta_text' => Setting::getValue('header_cta_text'),
            'header_cta_url' => Setting::getValue('header_cta_url'),
            'header_sticky' => Setting::getValue('header_sticky'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->statePath('data')->schema([
            Tabs::make('Appearance')
                ->tabs([
                    Tabs\Tab::make('Logo & Favicon')
                        ->schema([
                            Section::make('Logos')
                                ->schema([
                                    FileUpload::make('logo_light')
                                        ->label('Logo (Light Background)')
                                        ->image()
                                        ->nullable(),
                                    FileUpload::make('logo_dark')
                                        ->label('Logo (Dark Background)')
                                        ->image()
                                        ->nullable(),
                                    FileUpload::make('logo_mobile')
                                        ->label('Mobile Logo')
                                        ->image()
                                        ->nullable(),
                                ])->columns(3),
                            Section::make('Other Images')
                                ->schema([
                                    FileUpload::make('favicon')
                                        ->label('Favicon (16x16 or 32x32)')
                                        ->image()
                                        ->nullable(),
                                    FileUpload::make('og_image')
                                        ->label('OG Image (Social Share - 1200x630)')
                                        ->image()
                                        ->nullable(),
                                ])->columns(2),
                        ]),

                    Tabs\Tab::make('Colors')
                        ->schema([
                            Section::make('Brand Colors')
                                ->schema([
                                    TextInput::make('primary_color')
                                        ->label('Primary Color (Hex)')
                                        ->placeholder('#343C90'),
                                    TextInput::make('secondary_color')
                                        ->label('Secondary Color (Hex)')
                                        ->placeholder('#E05522'),
                                    TextInput::make('accent_color')
                                        ->label('Accent Color (Hex)')
                                        ->placeholder('#1F2937'),
                                ])->columns(3),
                        ]),

                    Tabs\Tab::make('Header Options')
                        ->schema([
                            Toggle::make('header_sticky')
                                ->label('Sticky Header'),
                            TextInput::make('header_cta_text')
                                ->label('Header CTA Button Text'),
                            TextInput::make('header_cta_url')
                                ->label('Header CTA Button URL'),
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
        
        $this->notify('success', 'Appearance settings saved successfully.');
    }
}
