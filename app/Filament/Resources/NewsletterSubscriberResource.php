<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Newsletter';
    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label('Name'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active'),
                Forms\Components\Toggle::make('is_verified')
                    ->label('Verified'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_verified')
                    ->label('Verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('Subscribed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('subscribed_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(fn($query) => $query->where('is_verified', true))
                    ->label('Verified Only'),
                Tables\Filters\Filter::make('unverified')
                    ->query(fn($query) => $query->where('is_verified', false))
                    ->label('Unverified'),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn($query) => $query->where('is_active', false))
                    ->label('Unsubscribed'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(NewsletterSubscriber $record) => $record->verify())
                    ->visible(fn(NewsletterSubscriber $record) => !$record->is_verified),
                Tables\Actions\Action::make('unsubscribe')
                    ->label('Unsubscribe')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn(NewsletterSubscriber $record) => $record->unsubscribe())
                    ->visible(fn(NewsletterSubscriber $record) => $record->is_active),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify')
                        ->label('Verify Selected')
                        ->action(fn($records) => $records->each->verify()),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function ($records) {
                            $csv = "Email,Name,Verified,Active,Subscribed\n";
                            foreach ($records as $record) {
                                $csv .= "{$record->email},{$record->name}," . 
                                    ($record->is_verified ? 'Yes' : 'No') . ',' .
                                    ($record->is_active ? 'Yes' : 'No') . ',' .
                                    $record->subscribed_at->format('Y-m-d H:i:s') . "\n";
                            }
                            
                            return response()->streamDownload(
                                fn() => print($csv),
                                'newsletter-subscribers.csv'
                            );
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'view' => Pages\ViewNewsletterSubscriber::route('/{record}'),
            'edit' => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Cache::remember('newsletter_subscribers_count', 60, function () {
            return NewsletterSubscriber::active()->count();
        });
    }
}
