<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ChartOfAccountResource\Pages;
use App\Models\ChartOfAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Chart of Accounts';
    protected static ?int $navigationSort = 30;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'finance']) || $user->can('accounting.manage'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Account Information')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(20)
                            ->unique(ChartOfAccount::class, 'code', fn($record) => $record),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('Classification')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options(ChartOfAccount::TYPES)
                            ->required()
                            ->reactive(),
                        Forms\Components\Select::make('category')
                            ->options(fn($get) => self::getCategoriesForType($get('type')))
                            ->required(),
                        Forms\Components\Select::make('normal_balance')
                            ->options(ChartOfAccount::NORMAL_BALANCES)
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Hierarchy')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Account')
                            ->relationship('parent', 'name', fn($query) => $query->whereNull('parent_id'))
                            ->searchable()
                            ->preload(),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\Toggle::make('is_system')
                            ->disabled()
                            ->tooltip('System accounts cannot be created manually'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors(fn($state) => match($state) {
                        'asset' => 'info',
                        'liability' => 'danger',
                        'equity' => 'warning',
                        'revenue' => 'success',
                        'expense' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('balance')
                    ->money('SAR')
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->balance),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_system')
                    ->boolean()
                    ->label('System'),
            ])
            ->defaultSort('code')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(ChartOfAccount::TYPES),
                Tables\Filters\SelectFilter::make('category')
                    ->options(ChartOfAccount::CATEGORIES),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Only'),
                Tables\Filters\TernaryFilter::make('is_system')
                    ->label('System Accounts'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_ledger')
                    ->label('View Ledger')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->url(fn($record) => route('filament.admin.resources.ledger-entries.index', ['tableFilters[account_id][value]' => $record->id])),
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
            'index' => Pages\ListChartOfAccounts::route('/'),
            'view' => Pages\ViewChartOfAccount::route('/{record}'),
            'edit' => Pages\EditChartOfAccount::route('/{record}/edit'),
        ];
    }

    public static function getCategoriesForType(?string $type): array
    {
        if (!$type) {
            return ChartOfAccount::CATEGORIES;
        }

        return match($type) {
            'asset' => [
                'current_asset' => 'Current Asset',
                'fixed_asset' => 'Fixed Asset',
            ],
            'liability' => [
                'current_liability' => 'Current Liability',
                'long_term_liability' => 'Long Term Liability',
            ],
            'equity' => [
                'owner_equity' => 'Owner Equity',
            ],
            'revenue' => [
                'operating_revenue' => 'Operating Revenue',
                'non_operating_revenue' => 'Non-Operating Revenue',
            ],
            'expense' => [
                'operating_expense' => 'Operating Expense',
                'non_operating_expense' => 'Non-Operating Expense',
            ],
            default => [],
        };
    }
}
