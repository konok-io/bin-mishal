<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Blog/News';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Title (English)')
                            ->required(),
                        Forms\Components\TextInput::make('title_bn')
                            ->label('Title (Bengali)'),
                        Forms\Components\TextInput::make('title_ar')
                            ->label('Title (Arabic)'),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name'),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Featured Image')
                            ->image(),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Excerpt (English)')
                            ->rows(2),
                        Forms\Components\Textarea::make('excerpt_bn')
                            ->label('Excerpt (Bengali)')
                            ->rows(2),
                        Forms\Components\Textarea::make('excerpt_ar')
                            ->label('Excerpt (Arabic)')
                            ->rows(2),
                    ]),

                Forms\Components\Section::make('Full Content')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Content (English)'),
                        Forms\Components\RichEditor::make('content_bn')
                            ->label('Content (Bengali)'),
                        Forms\Components\RichEditor::make('content_ar')
                            ->label('Content (Arabic)'),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(2),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords'),
                        Forms\Components\FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image(),
                    ])->collapsible(),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->default(false),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Published At'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
