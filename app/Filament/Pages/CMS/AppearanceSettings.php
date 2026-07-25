<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\BasePage;
use Filament\Forms\Form;
use Filament\Schemas;
use Filament\Schemas\Schema;

class AppearanceSettings extends BasePage
{
    protected static ?string $title = 'Appearance Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::first()?->settings ?? [];
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form->stateLocation('data')->schema([
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
                ])->columns(2),

            Schemas\Components\Section::make('Brand Colors')
                ->schema([
                    Schemas\Components\ColorPicker::make('primary_color')
                        ->label('Primary Color')
                        ->default('#059669'),
                    Schemas\Components\ColorPicker::make('secondary_color')
                        ->label('Secondary Color')
                        ->default('#047857'),
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
