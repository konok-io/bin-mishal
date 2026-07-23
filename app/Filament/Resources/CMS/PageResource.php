<?php

declare(strict_types=1);

namespace App\Filament\Resources\CMS;

use App\Filament\Resources\CMS\PageResource\Pages;
use App\Models\CMS\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationLabel = 'Pages';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page')
                    ->tabs([
                        // Content Tab
                        Forms\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Forms\Components\Section::make('Basic Info')
                                    ->schema([
                                        Forms\Components\Tabs::make('Titles')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title.en')
                                                            ->label('Title')
                                                            ->required(),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title.bn')
                                                            ->label('Title'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title.ar')
                                                            ->label('Title'),
                                                    ]),
                                            ]),
                                        Forms\Components\Tabs::make('Slugs')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('slug.en')
                                                            ->label('Slug')
                                                            ->unique(Page::class, 'slug->en', ignoreRecord: true),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('slug.bn')
                                                            ->label('Slug'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('slug.ar')
                                                            ->label('Slug'),
                                                    ]),
                                            ]),
                                    ])->columns(2),
                            ]),

                        // Sections Tab
                        Forms\Components\Tabs\Tab::make('Sections')
                            ->schema([
                                Forms\Components\Section::make('Page Sections')
                                    ->description('Drag to reorder sections')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\Select::make('section_type')
                                                    ->label('Section Type')
                                                    ->options(config('page_sections.types', []))
                                                    ->required()
                                                    ->reactive(),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Admin Label')
                                                    ->required(),
                                                Forms\Components\Toggle::make('status')
                                                    ->label('Active')
                                                    ->default(true),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0),
                                    ]),
                            ]),

                        // Hero Tab
                        Forms\Components\Tabs\Tab::make('Hero')
                            ->schema([
                                Forms\Components\Section::make('Hero Settings')
                                    ->schema([
                                        Forms\Components\Select::make('hero_type')
                                            ->label('Hero Type')
                                            ->options(Page::HERO_TYPES),
                                        Forms\Components\FileUpload::make('hero_image')
                                            ->label('Hero Image')
                                            ->image()
                                            ->nullable(),
                                        Forms\Components\Tabs::make('Hero Content')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_title.en')
                                                            ->label('Hero Title'),
                                                        Forms\Components\TextInput::make('hero_subtitle.en')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_title.bn')
                                                            ->label('Hero Title'),
                                                        Forms\Components\TextInput::make('hero_subtitle.bn')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('hero_title.ar')
                                                            ->label('Hero Title'),
                                                        Forms\Components\TextInput::make('hero_subtitle.ar')
                                                            ->label('Hero Subtitle'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),

                        // SEO Tab
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\Section::make('Meta Tags')
                                    ->schema([
                                        Forms\Components\Tabs::make('Meta')
                                            ->tabs([
                                                Forms\Components\Tabs\Tab::make('English')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('meta_title.en')
                                                            ->label('Meta Title'),
                                                        Forms\Components\Textarea::make('meta_description.en')
                                                            ->label('Meta Description')
                                                            ->rows(3),
                                                        Forms\Components\TextInput::make('meta_keywords.en')
                                                            ->label('Keywords'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('বাংলা')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('meta_title.bn')
                                                            ->label('Meta Title'),
                                                        Forms\Components\Textarea::make('meta_description.bn')
                                                            ->label('Meta Description'),
                                                    ]),
                                                Forms\Components\Tabs\Tab::make('العربية')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('meta_title.ar')
                                                            ->label('Meta Title'),
                                                        Forms\Components\Textarea::make('meta_description.ar')
                                                            ->label('Meta Description'),
                                                    ]),
                                            ]),
                                    ]),
                                Forms\Components\Section::make('Additional SEO')
                                    ->schema([
                                        Forms\Components\FileUpload::make('og_image')
                                            ->label('OG Image')
                                            ->image()
                                            ->nullable(),
                                        Forms\Components\TextInput::make('canonical_url')
                                            ->label('Canonical URL'),
                                        Forms\Components\Toggle::make('noindex')
                                            ->label('No Index'),
                                        Forms\Components\TextInput::make('schema_type')
                                            ->label('Schema.org Type')
                                            ->placeholder('WebPage'),
                                    ]),
                            ]),

                        // Settings Tab
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Forms\Components\Section::make('Page Settings')
                                    ->schema([
                                        Forms\Components\Select::make('template')
                                            ->label('Template')
                                            ->options(Page::TEMPLATES)
                                            ->default('default'),
                                        Forms\Components\Select::make('parent_id')
                                            ->label('Parent Page')
                                            ->relationship('parent', 'title')
                                            ->searchable()
                                            ->nullable(),
                                        Forms\Components\TextInput::make('order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0),
                                        Forms\Components\Toggle::make('is_homepage')
                                            ->label('Set as Homepage'),
                                        Forms\Components\Toggle::make('is_system')
                                            ->label('System Page'),
                                    ])->columns(2),
                                Forms\Components\Section::make('Display Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('show_header')
                                            ->label('Show Header')
                                            ->default(true),
                                        Forms\Components\Toggle::make('show_footer')
                                            ->label('Show Footer')
                                            ->default(true),
                                        Forms\Components\Toggle::make('show_breadcrumb')
                                            ->label('Show Breadcrumb')
                                            ->default(true),
                                    ])->columns(3),
                                Forms\Components\Section::make('Publishing')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                                'scheduled' => 'Scheduled',
                                            ])
                                            ->default('draft'),
                                        Forms\Components\DateTimePicker::make('published_at')
                                            ->label('Publish Date'),
                                        Forms\Components\DateTimePicker::make('scheduled_at')
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
