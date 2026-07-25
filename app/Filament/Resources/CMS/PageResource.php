<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS;

use App\Filament\Resources\CMS\PageResource\Pages;
use App\Models\CMS\Page;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class PageResource extends BaseResource
{
    protected static ?string $model = Page::class;

    public static function getNavigationLabel(): string
    {
        return 'Page';
    }

    public static function getNavigationGroup(): string
    {
        return 'CMS';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Tabs::make('Page')
                    ->tabs([
                        // Content Tab
                        Schemas\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Schemas\Components\Section::make('Basic Info')
                                    ->schema([
                                        Schemas\Components\Tabs::make('Titles')
                                            ->tabs([
                                                Schemas\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('title.en')
                                                            ->label('Title')
                                                            ->required(),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('title.bn')
                                                            ->label('Title'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('title.ar')
                                                            ->label('Title'),
                                                    ]),
                                            ]),
                                        Schemas\Components\Tabs::make('Slugs')
                                            ->tabs([
                                                Schemas\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('slug.en')
                                                            ->label('Slug')
                                                            ->unique(Page::class, 'slug->en', ignoreRecord: true),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('slug.bn')
                                                            ->label('Slug'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('slug.ar')
                                                            ->label('Slug'),
                                                    ]),
                                            ]),
                                    ])->columns(2),
                            ]),

                        // Sections Tab
                        Schemas\Components\Tabs\Tab::make('Sections')
                            ->schema([
                                Schemas\Components\Section::make('Page Sections')
                                    ->description('Drag to reorder sections')
                                    ->schema([
                                        Schemas\Components\Repeater::make('sections')
                                            ->relationship()
                                            ->schema([
                                                Schemas\Components\Select::make('section_type')
                                                    ->label('Section Type')
                                                    ->options(config('page_sections.types', []))
                                                    ->required()
                                                    ->reactive(),
                                                Schemas\Components\TextInput::make('name')
                                                    ->label('Admin Label')
                                                    ->required(),
                                                Schemas\Components\Toggle::make('status')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0),
                                    ]),
                            ]),

                        // Hero Tab
                        Schemas\Components\Tabs\Tab::make('Hero')
                            ->schema([
                                Schemas\Components\Section::make('Hero Settings')
                                    ->schema([
                                        Schemas\Components\Select::make('hero_type')
                                            ->label('Hero Type')
                                            ->options(Page::HERO_TYPES),
                                        Schemas\Components\FileUpload::make('hero_image')
                                            ->label('Hero Image')
                                            ->image()
                                            ->nullable(),
                                        Schemas\Components\Tabs::make('Hero Content')
                                            ->tabs([
                                                Schemas\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('hero_title.en')
                                                            ->label('Hero Title'),
                                                        Schemas\Components\TextInput::make('hero_subtitle.en')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('hero_title.bn')
                                                            ->label('Hero Title'),
                                                        Schemas\Components\TextInput::make('hero_subtitle.bn')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('hero_title.ar')
                                                            ->label('Hero Title'),
                                                        Schemas\Components\TextInput::make('hero_subtitle.ar')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),

                        // SEO Tab
                        Schemas\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Schemas\Components\Section::make('Meta Tags')
                                    ->schema([
                                        Schemas\Components\Tabs::make('Meta')
                                            ->tabs([
                                                Schemas\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('meta_title.en')
                                                            ->label('Meta Title'),
                                                        Schemas\Components\Textarea::make('meta_description.en')
                                                            ->label('Meta Description')
                                                            ->rows(3),
                                                        Schemas\Components\TextInput::make('meta_keywords.en')
                                                            ->label('Keywords'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('meta_title.bn')
                                                            ->label('Meta Title'),
                                                        Schemas\Components\Textarea::make('meta_description.bn')
                                                            ->label('Meta Description'),
                                                    ]),
                                                Schemas\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Schemas\Components\TextInput::make('meta_title.ar')
                                                            ->label('Meta Title'),
                                                        Schemas\Components\Textarea::make('meta_description.ar')
                                                            ->label('Meta Description'),
                                                    ]),
                                            ]),
                                    ]),
                                Schemas\Components\Section::make('Additional SEO')
                                    ->schema([
                                        Schemas\Components\FileUpload::make('og_image')
                                            ->label('OG Image')
                                            ->image()
                                            ->nullable(),
                                        Schemas\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL'),
                                        Schemas\Components\Toggle::make('noindex')
                                            ->label('No Index'),
                                        Schemas\Components\TextInput::make('schema_type')
                                            ->label('Schema.org Type')
                                            ->placeholder('WebPage'),
                                    ]),
                            ]),

                        // Settings Tab
                        Schemas\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Schemas\Components\Section::make('Page Settings')
                                    ->schema([
                                        Schemas\Components\Select::make('template')
                                            ->label('Template')
                                            ->options(Page::TEMPLATES)
                                            ->default('default'),
                                        Schemas\Components\Select::make('parent_id')
                                            ->label('Parent Page')
                                            ->relationship('parent', 'title')
                                            ->searchable()
                                            ->nullable(),
                                        Schemas\Components\TextInput::make('order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0),
                                        Schemas\Components\Toggle::make('is_homepage')
                                            ->label('Set as Homepage'),
                                        Schemas\Components\Toggle::make('is_system')
                                            ->label('System Page'),
                                    ])->columns(2),
                                Schemas\Components\Section::make('Display Options')
                                    ->schema([
                                        Schemas\Components\Toggle::make('show_header')
                                            ->label('Show Header')
                                            ->default(true),
                                        Schemas\Components\Toggle::make('show_footer')
                                            ->label('Show Footer')
                                            ->default(true),
                                        Schemas\Components\Toggle::make('show_breadcrumb')
                                            ->label('Show Breadcrumb')
                                            ->default(true),
                                    ])->columns(3),
                                Schemas\Components\Section::make('Publishing')
                                    ->schema([
                                        Schemas\Components\Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                'scheduled' => 'Scheduled',
                                            ])
                                            ->default('draft'),
                                        Schemas\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date'),
                                        Schemas\Components\DateTimePicker::make('scheduled_at')
                                            ->label('Schedule Date'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->getStateUsing(fn($record) => $record->translated_title)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('template')
                    ->label('Template'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'info' => 'scheduled',
                    ]),
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Home')
                    ->boolean(),
                Tables\Columns\IconColumn::make('show_header')
                    ->label('Header')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ]),
                Tables\Filters\TernaryFilter::make('is_homepage'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => "/{$record->slug['en']}?preview=1")
                        ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
