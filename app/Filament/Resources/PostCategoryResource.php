<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Models\PostCategory;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class PostCategoryResource extends BaseResource
{
    protected static ?string $model = PostCategory::class;

    public static function getNavigationLabel(): string
    {
        return 'Post Categories';
    }

    public static function getNavigationGroup(): string
    {
        return 'CMS';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Basic Information')
                    ->schema([
                        Schemas\Components\TextInput::make('name')
                            ->label('Name (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('name_bn')
                            ->label('Name (Bengali)'),
                        Schemas\Components\TextInput::make('name_ar')
                            ->label('Name (Arabic)'),
                        Schemas\Components\TextInput::make('icon')
                            ->label('Icon (FontAwesome)')
                            ->placeholder('fas fa-folder'),
                    ])->columns(2),

                Schemas\Components\Section::make('Details')
                    ->schema([
                        Schemas\Components\Textarea::make('description')
                            ->label('Description (English)')
                            ->rows(2),
                        Schemas\Components\Textarea::make('description_bn')
                            ->label('Description (Bengali)')
                            ->rows(2),
                        Schemas\Components\Textarea::make('description_ar')
                            ->label('Description (Arabic)')
                            ->rows(2),
                        Schemas\Components\ColorPicker::make('color')
                            ->label('Color'),
                    ]),

                Schemas\Components\Section::make('Settings')
                    ->schema([
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Schemas\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
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
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }
}
