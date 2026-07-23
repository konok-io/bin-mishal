<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Inbox';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = ContactMessage::unread()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sender Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->url(fn($state) => 'mailto:' . $state),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->disabled(),
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options(ContactMessage::TYPES)
                            ->disabled(),
                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->disabled()
                            ->rows(6),
                    ]),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('Mark as Read'),
                        Forms\Components\Toggle::make('is_replied')
                            ->label('Mark as Replied'),
                        Forms\Components\Toggle::make('is_spam')
                            ->label('Mark as Spam'),
                        Forms\Components\Textarea::make('reply_note')
                            ->label('Reply Note (Internal)')
                            ->rows(2)
                            ->helperText('Internal note about your reply'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(3)
                            ->helperText('Private notes only visible to admins'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => ContactMessage::TYPES[$state] ?? $state),
                Tables\Columns\IconColumn::make('is_replied')
                    ->label('Replied')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon(''),
                Tables\Columns\IconColumn::make('is_spam')
                    ->label('Spam')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon(''),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('unread')
                    ->label('Unread Only')
                    ->query(fn(Builder $query): Builder => $query->where('is_read', false)->where('is_spam', false))
                    ->default(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(ContactMessage::TYPES),
                Tables\Filters\SelectFilter::make('is_replied')
                    ->label('Reply Status')
                    ->options([
                        '1' => 'Replied',
                        '0' => 'Not Replied',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_read')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->action(fn(ContactMessage $record) => $record->markAsRead())
                    ->visible(fn(ContactMessage $record) => !$record->is_read),
                Tables\Actions\Action::make('mark_replied')
                    ->label('Mark Replied')
                    ->icon('heroicon-o-paper-airplane')
                    ->form([
                        Forms\Components\Textarea::make('reply_note')
                            ->label('Reply Note')
                            ->rows(2),
                    ])
                    ->action(function (ContactMessage $record, array $data) {
                        $record->markAsReplied($data['reply_note'] ?? null);
                    })
                    ->visible(fn(ContactMessage $record) => !$record->is_replied),
                Tables\Actions\Action::make('mark_spam')
                    ->label('Mark Spam')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('danger')
                    ->action(fn(ContactMessage $record) => $record->markAsSpam())
                    ->visible(fn(ContactMessage $record) => !$record->is_spam),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_all_read')
                        ->label('Mark All as Read')
                        ->action(fn($records) => $records->each->markAsRead()),
                    Tables\Actions\BulkAction::make('mark_all_spam')
                        ->label('Mark All as Spam')
                        ->action(fn($records) => $records->each->markAsSpam()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
