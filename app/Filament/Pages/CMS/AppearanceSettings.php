<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Schemas;
use Filament\Schemas\Schema;

class AppearanceSettings extends Page
{
    protected static ?string $title = 'Appearance Settings';

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
        return $form->schema([
            Schemas\Components\Tabs::make('Appearance')
                ->tabs([
                    Schemas\Components\Tabs\Tab::make('Logo & Favicon')
                        ->schema([
                            Schemas\Components\Section::make('Logos')
                                ->schema([
                                    Schemas\Components\FileUpload::make('logo_light')
                                        ->label('Logo (Light Background)')
                                        ->image()
                                        ->nullable(),
                                    Schemas\Components\FileUpload::make('logo_dark')
                                        ->label('Logo (Dark Background)')
                                        ->image()
                                        ->nullable(),
                                    Schemas\Components\FileUpload::make('logo_mobile')
                                        ->label('Mobile Logo')
                                        ->image()
                                        ->nullable(),
                                ])->columns(3),
                            Schemas\Components\Section::make('Other Images')
                                ->schema([
                                    Schemas\Components\FileUpload::make('favicon')
                                        ->label('Favicon (16x16 or 32x32)')
                                        ->image()
                                        ->nullable(),
                                    Schemas\Components\FileUpload::make('og_image')
                                        ->label('OG Image (Social Share - 1200x630)')
                                        ->image()
                                        ->nullable(),
                                ])->columns(2),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Colors')
                        ->schema([
                            Schemas\Components\Section::make('Brand Colors')
                                ->schema([
                                    Schemas\Components\TextInput::make('primary_color')
                                        ->label('Primary Color (Hex)')
                                        ->placeholder('#343C90'),
                                    Schemas\Components\TextInput::make('secondary_color')
                                        ->label('Secondary Color (Hex)')
                                        ->placeholder('#E05522'),
                                    Schemas\Components\TextInput::make('accent_color')
                                        ->label('Accent Color (Hex)')
                                        ->placeholder('#1F2937'),
                                ])->columns(3),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Header Options')
                        ->schema([
                            Schemas\Components\Toggle::make('header_sticky')
                                ->label('Sticky Header'),
                            Schemas\Components\TextInput::make('header_cta_text')
                                ->label('Header CTA Button Text'),
                            Schemas\Components\TextInput::make('header_cta_url')
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
