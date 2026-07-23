<?php

declare(strict_types=1);

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Grid;

class ViewContactMessage extends ViewRecord
{
    protected static ?string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_read')
                ->label('Mark as Read')
                ->icon('heroicon-o-check')
                ->action(fn() => $this->record->markAsRead())
                ->visible(fn() => !$this->record->is_read),

            Action::make('mark_replied')
                ->label('Mark as Replied')
                ->icon('heroicon-o-paper-airplane')
                ->form([
                    \Filament\Forms\Components\Textarea::make('reply_note')
                        ->label('Reply Note')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $this->record->markAsReplied($data['reply_note'] ?? null);
                })
                ->visible(fn() => !$this->record->is_replied),

            Action::make('mark_spam')
                ->label('Mark as Spam')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->action(fn() => $this->record->markAsSpam())
                ->visible(fn() => !$this->record->is_spam),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Sender Information')
                    ->schema([
                        TextEntry::make('name')->label('Name'),
                        TextEntry::make('email')->label('Email')->copyable(),
                        TextEntry::make('phone')->label('Phone'),
                    ])->columns(3),

                Section::make('Message')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('subject')->label('Subject'),
                                TextEntry::make('type')
                                    ->label('Type')
                                    ->formatStateUsing(fn($state) => \App\Models\ContactMessage::TYPES[$state] ?? $state),
                            ]),
                        TextEntry::make('message')
                            ->label('Message')
                            ->html(),
                    ]),

                Section::make('Status')
                    ->schema([
                        IconEntry::make('is_read')
                            ->label('Read Status')
                            ->boolean()
                            ->formatStateUsing(fn($state) => $state ? 'Read' : 'Unread'),
                        TextEntry::make('read_at')
                            ->label('Read At')
                            ->dateTime(),
                        TextEntry::make('reader.name')
                            ->label('Read By'),
                        IconEntry::make('is_replied')
                            ->label('Reply Status')
                            ->boolean()
                            ->formatStateUsing(fn($state) => $state ? 'Replied' : 'Pending'),
                        TextEntry::make('replied_at')
                            ->label('Replied At')
                            ->dateTime(),
                        TextEntry::make('replier.name')
                            ->label('Replied By'),
                        TextEntry::make('reply_note')
                            ->label('Reply Note'),
                        IconEntry::make('is_spam')
                            ->label('Spam Status')
                            ->boolean()
                            ->formatStateUsing(fn($state) => $state ? 'Spam' : 'Not Spam'),
                    ])->columns(4),

                Section::make('Admin Notes')
                    ->schema([
                        TextEntry::make('admin_notes')
                            ->label('Admin Notes')
                            ->html(),
                    ])->collapsible(),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('created_at')->label('Received')->dateTime(),
                        TextEntry::make('ip_address')->label('IP Address'),
                    ])->columns(2),
            ]);
    }
}
