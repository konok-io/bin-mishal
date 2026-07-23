<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'HR';
    protected static ?string $navigationLabel = 'Job Postings';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Tabs::make('Job Details')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Basic Info')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Job Title (English)')
                                            ->required(),
                                        Forms\Components\TextInput::make('title_bn')
                                            ->label('Job Title (Bengali)'),
                                        Forms\Components\TextInput::make('title_ar')
                                            ->label('Job Title (Arabic)'),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Department & Location')
                                    ->schema([
                                        Forms\Components\Select::make('department')
                                            ->label('Department')
                                            ->options(Job::DEPARTMENTS)
                                            ->required(),
                                        Forms\Components\TextInput::make('department_bn')
                                            ->label('Department (Bengali)'),
                                        Forms\Components\TextInput::make('department_ar')
                                            ->label('Department (Arabic)'),
                                        Forms\Components\TextInput::make('location')
                                            ->label('Location (English)')
                                            ->required(),
                                        Forms\Components\TextInput::make('location_bn')
                                            ->label('Location (Bengali)'),
                                        Forms\Components\TextInput::make('location_ar')
                                            ->label('Location (Arabic)'),
                                        Forms\Components\Select::make('country')
                                            ->label('Country')
                                            ->options([
                                                'SA' => 'Saudi Arabia',
                                                'BD' => 'Bangladesh',
                                                'AE' => 'UAE',
                                                'QA' => 'Qatar',
                                            ])
                                            ->default('SA'),
                                    ]),
                            ])->columnSpanFull(),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Select::make('employment_type')
                                    ->label('Employment Type')
                                    ->options(Job::EMPLOYMENT_TYPES)
                                    ->default('full_time'),
                                Forms\Components\Select::make('experience_level')
                                    ->label('Experience Level')
                                    ->options(Job::EXPERIENCE_LEVELS)
                                    ->default('mid'),
                                Forms\Components\DatePicker::make('deadline')
                                    ->label('Application Deadline'),
                            ])->columns(3),

                        Forms\Components\Section::make('Salary Range')
                            ->schema([
                                Forms\Components\TextInput::make('salary_min')
                                    ->label('Minimum Salary (SAR)')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Forms\Components\TextInput::make('salary_max')
                                    ->label('Maximum Salary (SAR)')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Forms\Components\Toggle::make('salary_visible')
                                    ->label('Show salary on public page')
                                    ->default(false),
                            ])->columns(3),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Column::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'closed' => 'Closed',
                                    ])
                                    ->default('draft'),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Job'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('department')
                    ->label('Department'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location'),
                Tables\Columns\BadgeColumn::make('employment_type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => Job::EMPLOYMENT_TYPES[$state] ?? $state),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'success' => 'published',
                        'danger' => 'closed',
                    ]),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Applications')
                    ->counts('applications')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'closed' => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->options(Job::DEPARTMENTS),
                Tables\Filters\SelectFilter::make('employment_type')
                    ->options(Job::EMPLOYMENT_TYPES),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_applications')
                    ->label('View Applications')
                    ->icon('heroicon-o-users')
                    ->url(fn(Job $record) => route('filament.admin.resources.job-applications.index', ['tableFilters[job_id][value]' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->action(fn($records) => $records->each->update(['status' => 'published'])),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view' => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
