<?php

namespace App\Filament\Resources\NewsletterSubscriberResource\Pages;

use App\Filament\Resources\NewsletterSubscriberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterSubscribers extends ListRecords
{
    protected static ?string $resource = NewsletterSubscriberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export All')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $subscribers = \App\Models\NewsletterSubscriber::all();
                    $csv = "Email,Name,Verified,Active,Subscribed\n";
                    foreach ($subscribers as $record) {
                        $csv .= "{$record->email},{$record->name}," . 
                            ($record->is_verified ? 'Yes' : 'No') . ',' .
                            ($record->is_active ? 'Yes' : 'No') . ',' .
                            $record->subscribed_at->format('Y-m-d H:i:s') . "\n";
                    }
                    
                    return response()->streamDownload(
                        fn() => print($csv),
                        'all-subscribers.csv'
                    );
                }),
        ];
    }
}
