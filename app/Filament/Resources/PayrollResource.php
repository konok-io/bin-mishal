<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollResource\Pages;
use App\Models\Payroll;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'HR';
    protected static ?string $navigationLabel = 'Payroll';
    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('payroll.view'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Employee & Period')
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->label('Employee')
                            ->relationship('employee', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('payroll_month')
                            ->label('Month')
                            ->placeholder('01-12')
                            ->required(),
                        Forms\Components\TextInput::make('payroll_year')
                            ->label('Year')
                            ->numeric()
                            ->default(date('Y'))
                            ->required(),
                        Forms\Components\DatePicker::make('pay_date')
                            ->label('Payment Date'),
                    ])->columns(4),

                Forms\Components\Section::make('Earnings')
                    ->schema([
                        Forms\Components\TextInput::make('basic_salary')
                            ->label('Basic Salary')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('housing_allowance')
                            ->label('Housing Allowance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('transport_allowance')
                            ->label('Transport Allowance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('food_allowance')
                            ->label('Food Allowance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('other_allowance')
                            ->label('Other Allowance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('overtime_hours')
                            ->label('Overtime Hours')
                            ->numeric(),
                        Forms\Components\TextInput::make('overtime_amount')
                            ->label('Overtime Amount')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('bonus')
                            ->label('Bonus')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('gross_salary')
                            ->label('Gross Salary')
                            ->numeric()
                            ->prefix('SAR'),
                    ])->columns(3),

                Forms\Components\Section::make('Deductions')
                    ->schema([
                        Forms\Components\TextInput::make('gosi_deduction')
                            ->label('GOSI Deduction')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('health_insurance')
                            ->label('Health Insurance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('absence_days')
                            ->label('Absence Days')
                            ->numeric(),
                        Forms\Components\TextInput::make('absence_deduction')
                            ->label('Absence Deduction')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('loan_deduction')
                            ->label('Loan Deduction')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('loan_balance')
                            ->label('Outstanding Loan Balance')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('other_deduction')
                            ->label('Other Deductions')
                            ->numeric()
                            ->prefix('SAR'),
                        Forms\Components\TextInput::make('total_deductions')
                            ->label('Total Deductions')
                            ->numeric()
                            ->prefix('SAR'),
                    ])->columns(4),

                Forms\Components\Section::make('Net Pay')
                    ->schema([
                        Forms\Components\TextInput::make('net_salary')
                            ->label('Net Salary')
                            ->numeric()
                            ->prefix('SAR')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'reviewed' => 'HR Reviewed',
                                'approved' => 'Approved',
                                'paid' => 'Paid/Locked',
                            ])
                            ->default('draft'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.employee_id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payroll_month')
                    ->label('Month')
                    ->formatStateUsing(fn($state) => date('F', mktime(0, 0, 0, (int)$state, 1))),
                Tables\Columns\TextColumn::make('payroll_year')
                    ->label('Year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gross_salary')
                    ->label('Gross')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_deductions')
                    ->label('Deductions')
                    ->money('SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_salary')
                    ->label('Net')
                    ->money('SAR')
                    ->sortable()
                    ->color('success'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'reviewed',
                        'success' => 'approved',
                        'info' => 'paid',
                    ]),
                Tables\Columns\TextColumn::make('pay_date')
                    ->label('Pay Date')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('payroll_year', 'desc')
            ->defaultSort('payroll_month', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'reviewed' => 'HR Reviewed',
                        'approved' => 'Approved',
                        'paid' => 'Paid/Locked',
                    ]),
                Tables\Filters\SelectFilter::make('payroll_year')
                    ->options(fn() => collect(range(date('Y') - 2, date('Y')))->mapWithKeys(fn($y) => [$y => $y])->toArray())
                    ->label('Year'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(fn(Payroll $record) => static::downloadPayslip($record)),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn(Payroll $record) => $record->update(['status' => 'approved']))
                    ->visible(fn(Payroll $record) => $record->status === 'reviewed'),
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark as Paid')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->action(fn(Payroll $record) => $record->update(['status' => 'paid']))
                    ->visible(fn(Payroll $record) => $record->status === 'approved'),
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
            'index' => Pages\ListPayrolls::route('/'),
            'view' => Pages\ViewPayroll::route('/{record}'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }

    public static function downloadPayslip(Payroll $payroll): \Symfony\Component\HttpFoundation\Response
    {
        $service = app(\App\Services\PayslipPdfService::class);
        $path = $service->generatePayslip($payroll);
        
        return response()->download(storage_path('app/public/' . $path));
    }
}
