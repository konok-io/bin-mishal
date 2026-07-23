<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PostCommentResource\Pages;
use App\Models\PostComment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostCommentResource extends Resource
{
    protected static ?string $model = PostComment::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Comments';
    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('post_id')
                    ->label('Post')
                    ->relationship('post', 'title')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Author Name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->rows(4),
                Forms\Components\Toggle::make('is_approved')
                    ->label('Approved'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title')
                    ->label('Post')
                    ->limit(30)
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('pending')
                    ->query(fn($query) => $query->where('is_approved', false)),
                Tables\Filters\Filter::make('approved')
                    ->query(fn($query) => $query->where('is_approved', true)),
                Tables\Filters\SelectFilter::make('post_id')
                    ->label('Post')
                    ->relationship('post', 'title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(PostComment $record) => $record->approve())
                    ->visible(fn(PostComment $record) => !$record->is_approved),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->action(fn($records) => $records->each->approve()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostComments::route('/'),
            'view' => Pages\ViewPostComment::route('/{record}'),
            'edit' => Pages\EditPostComment::route('/{record}/edit'),
        ];
    }
}
