<?php

declare(strict_types=1);

namespace App\Filament\Pages\CMS;

use App\Models\CMS\Setting;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Schemas;
use Filament\Schemas\Schema;

class SeoSettings extends Page
{
    protected static ?string $title = 'SEO Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'meta_title' => Setting::getValue('meta_title'),
            'meta_description' => Setting::getValue('meta_description'),
            'meta_keywords' => Setting::getValue('meta_keywords'),
            'google_analytics_id' => Setting::getValue('google_analytics_id'),
            'google_search_console' => Setting::getValue('google_search_console'),
            'bing_webmaster' => Setting::getValue('bing_webmaster'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Schemas\Components\Tabs::make('SEO')
                ->tabs([
                    Schemas\Components\Tabs\Tab::make('Meta Tags')
                        ->schema([
                            Schemas\Components\Section::make('Default Meta Tags')
                                ->description('These will be used as defaults across the site.')
                                ->schema([
                                    Schemas\Components\TextInput::make('meta_title')
                                        ->label('Default Meta Title')
                                        ->placeholder('Page Title | Site Name'),
                                    Schemas\Components\Textarea::make('meta_description')
                                        ->label('Default Meta Description')
                                        ->rows(3),
                                    Schemas\Components\TextInput::make('meta_keywords')
                                        ->label('Meta Keywords')
                                        ->placeholder('keyword1, keyword2, keyword3'),
                                ]),
                        ]),

                    Schemas\Components\Tabs\Tab::make('Webmaster Tools')
                        ->schema([
                            Schemas\Components\Section::make('Verification Codes')
                                ->description('Add verification codes for search engine tools.')
                                ->schema([
                                    Schemas\Components\TextInput::make('google_analytics_id')
                                        ->label('Google Analytics ID')
                                        ->placeholder('G-XXXXXXXXXX'),
                                    Schemas\Components\TextInput::make('google_search_console')
                                        ->label('Google Search Console Verification')
                                        ->placeholder('google-site-verification: xxxxxx'),
                                    Schemas\Components\TextInput::make('bing_webmaster')
                                        ->label('Bing Webmaster Verification')
                                        ->placeholder('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'),
                                ]),
                        ]),
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
        
        $this->notify('success', 'SEO settings saved successfully.');
    }
}
