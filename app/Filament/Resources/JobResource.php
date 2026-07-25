<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class JobResource extends BaseResource
{
    protected static ?string $model = Job::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('careers.view'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Group::make()
                    ->schema([
                        Schemas\Components\Tabs::make('Job Details')
                            ->tabs([
                                Schemas\Components\Tabs\Tab::make('Basic Info')
                                    ->schema([
                                        Schemas\Components\TextInput::make('title')
                                            ->label('Job Title (English)')
                                            ->required(),
                                        Schemas\Components\TextInput::make('title_bn')
                                            ->label('Job Title (Bengali)'),
                                        Schemas\Components\TextInput::make('title_ar')
                                            ->label('Job Title (Arabic)'),
                                    ]),
                                Schemas\Components\Tabs\Tab::make('Department & Location')
                                    ->schema([
                                        Schemas\Components\Select::make('department')
                                            ->label('Department')
                                            ->options(Job::DEPARTMENTS)
                                            ->required(),
                                        Schemas\Components\TextInput::make('department_bn')
                                            ->label('Department (Bengali)'),
                                        Schemas\Components\TextInput::make('department_ar')
                                            ->label('Department (Arabic)'),
                                        Schemas\Components\TextInput::make('location')
                                            ->label('Location (English)')
                                            ->required(),
                                        Schemas\Components\TextInput::make('location_bn')
                                            ->label('Location (Bengali)'),
                                        Schemas\Components\TextInput::make('location_ar')
                                            ->label('Location (Arabic)'),
                                        Schemas\Components\Select::make('country')
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

                        Schemas\Components\Group::make()
                            ->schema([
                                Schemas\Components\Select::make('employment_type')
                                    ->label('Employment Type')
                                    ->options(Job::EMPLOYMENT_TYPES)
                                    ->default('full_time'),
                                Schemas\Components\Select::make('experience_level')
                                    ->label('Experience Level')
                                    ->options(Job::EXPERIENCE_LEVELS)
                                    ->default('mid'),
                                Schemas\Components\DatePicker::make('deadline')
                                    ->label('Application Deadline'),
                            ])->columns(3),

                        Schemas\Components\Section::make('Salary Range')
                            ->schema([
                                Schemas\Components\TextInput::make('salary_min')
                                    ->label('Minimum Salary (SAR)')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Schemas\Components\TextInput::make('salary_max')
                                    ->label('Maximum Salary (SAR)')
                                    ->numeric()
                                    ->prefix('SAR'),
                                Schemas\Components\Toggle::make('salary_visible')
                                    ->label('Show salary on public page')
                                    ->default(false),
                            ])->columns(3),
                    ])->columnSpan(['lg' => 2]),

                Schemas\Components\Column::make()
                    ->schema([
                        Schemas\Components\Section::make('Status')
                            ->schema([
                                Schemas\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'closed' => 'Closed',
                                    ])
                                    ->default('draft'),
                                Schemas\Components\Toggle::make('is_featured')
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
