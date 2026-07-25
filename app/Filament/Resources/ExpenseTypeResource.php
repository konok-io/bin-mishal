<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseTypeResource\Pages;
use App\Models\ExpenseType;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseTypeResource extends BaseResource
{
    protected static ?string $model = ExpenseType::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('expense.manage'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Basic Information')
                    ->schema([
                        Schemas\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Schemas\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ExpenseType::class, 'slug', fn($record) => $record),
                        Schemas\Components\Textarea::make('description')
                            ->rows(3),
                    ])->columns(2),

                Schemas\Components\Section::make('Classification')
                    ->schema([
                        Schemas\Components\Select::make('category')
                            ->options(ExpenseType::CATEGORIES)
                            ->required(),
                        Schemas\Components\Select::make('payment_type')
                            ->options(ExpenseType::PAYMENT_TYPES)
                            ->required()
                            ->helperText('Reimbursable: Added to payroll. Deductible: Subtracted from payroll.'),
                    ])->columns(2),

                Schemas\Components\Section::make('Limits & Requirements')
                    ->schema([
                        Schemas\Components\TextInput::make('max_amount')
                            ->numeric()
                            ->prefix('SAR')
                            ->helperText('Leave empty for no limit'),
                        Schemas\Components\Toggle::make('requires_receipt')
                            ->label('Receipt Required')
                            ->default(true),
                        Schemas\Components\Toggle::make('requires_approval')
                            ->label('Requires Approval')
                            ->default(true),
                    ])->columns(3),

                Schemas\Components\Section::make('Settings')
                    ->schema([
                        Schemas\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        Schemas\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Schemas\Components\Select::make('approval_level')
                            ->options([1 => 'Level 1 (Manager)', 2 => 'Level 2 (HR)', 3 => 'Level 3 (Finance)'])
                            ->default(1),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('category')
                    ->colors(['info']),
                Tables\Columns\BadgeColumn::make('payment_type')
                    ->colors(fn($state) => match($state) {
                        'reimbursable' => 'success',
                        'deductible' => 'danger',
                        'both' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('max_amount')
                    ->money('SAR')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('requires_receipt')
                    ->icon(fn($state) => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->boolean()
                    ->label('Receipt'),
                Tables\Columns\TextColumn::make('claims_count')
                    ->counts('claims')
                    ->label('Claims'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(ExpenseType::CATEGORIES),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options(ExpenseType::PAYMENT_TYPES),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Only'),
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
            'index' => Pages\ListExpenseTypes::route('/'),
            'create' => Pages\CreateExpenseType::route('/create'),
            'edit' => Pages\EditExpenseType::route('/{record}/edit'),
        ];
    }
}
