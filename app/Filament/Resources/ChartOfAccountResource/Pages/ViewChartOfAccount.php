<?php

namespace App\Filament\Resources\ChartOfAccountResource\Pages;

use App\Filament\Resources\ChartOfAccountResource;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Components\Card;
use Filament\Resources\Pages\ViewRecord;

class ViewChartOfAccount extends ViewRecord
{
    protected static string $resource = ChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Account Details')
                    ->schema([
                        TextEntry::make('code'),
                        TextEntry::make('name'),
                        TextEntry::make('description'),
                    ])->columns(3),

                Section::make('Classification')
                    ->schema([
                        TextEntry::make('type')
                            ->badge(),
                        TextEntry::make('category')
                            ->badge(),
                        TextEntry::make('normal_balance')
                            ->label('Normal Balance'),
                    ])->columns(3),

                Section::make('Balance Summary')
                    ->schema([
                        Card::make([
                            TextEntry::make('debit_total')
                                ->label('Total Debits')
                                ->money('SAR'),
                        ]),
                        Card::make([
                            TextEntry::make('credit_total')
                                ->label('Total Credits')
                                ->money('SAR'),
                        ]),
                        Card::make([
                            TextEntry::make('balance')
                                ->label('Current Balance')
                                ->money('SAR'),
                        ]),
                    ])->columns(3),

                Section::make('Settings')
                    ->schema([
                        TextEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        TextEntry::make('is_system')
                            ->label('System Account')
                            ->boolean(),
                        TextEntry::make('parent.name')
                            ->label('Parent Account'),
                    ])->columns(3),
            ]);
    }
}
