<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'FAQs';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Question')
                    ->schema([
                        Forms\Components\TextInput::make('question')
                            ->label('Question (English)')
                            ->required(),
                        Forms\Components\TextInput::make('question_bn')
                            ->label('Question (Bengali)'),
                        Forms\Components\TextInput::make('question_ar')
                            ->label('Question (Arabic)'),
                    ]),

                Forms\Components\Section::make('Answer')
                    ->schema([
                        Forms\Components\Textarea::make('answer')
                            ->label('Answer (English)')
                            ->rows(4),
                        Forms\Components\Textarea::make('answer_bn')
                            ->label('Answer (Bengali)')
                            ->rows(4),
                        Forms\Components\Textarea::make('answer_ar')
                            ->label('Answer (Arabic)')
                            ->rows(4),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options(Faq::CATEGORIES),
                        Forms\Components\TextInput::make('service_type')
                            ->label('Service Type'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
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
