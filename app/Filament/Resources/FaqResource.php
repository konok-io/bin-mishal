<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class FaqResource extends BaseResource
{
    protected static ?string $model = Faq::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Question')
                    ->schema([
                        Schemas\Components\TextInput::make('question')
                            ->label('Question (English)')
                            ->required(),
                        Schemas\Components\TextInput::make('question_bn')
                            ->label('Question (Bengali)'),
                        Schemas\Components\TextInput::make('question_ar')
                            ->label('Question (Arabic)'),
                    ]),

                Schemas\Components\Section::make('Answer')
                    ->schema([
                        Schemas\Components\Textarea::make('answer')
                            ->label('Answer (English)')
                            ->rows(4),
                        Schemas\Components\Textarea::make('answer_bn')
                            ->label('Answer (Bengali)')
                            ->rows(4),
                        Schemas\Components\Textarea::make('answer_ar')
                            ->label('Answer (Arabic)')
                            ->rows(4),
                    ]),

                Schemas\Components\Section::make('Settings')
                    ->schema([
                        Schemas\Components\Select::make('category')
                            ->label('Category')
                            ->options(Faq::CATEGORIES),
                        Schemas\Components\TextInput::make('service_type')
                            ->label('Service Type'),
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
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->label('Category'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Faq::CATEGORIES),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
