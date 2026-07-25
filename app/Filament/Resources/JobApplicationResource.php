<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\JobApplicationResource\Pages;
use App\Models\JobApplication;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class JobApplicationResource extends BaseResource
{
    protected static ?string $model = JobApplication::class;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole(['super_admin', 'admin', 'hr']) || $user->can('applications.view'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Applicant Information')
                    ->schema([
                        Schemas\Components\TextInput::make('full_name')
                            ->label('Full Name')
                            ->disabled(),
                        Schemas\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled(),
                        Schemas\Components\TextInput::make('phone')
                            ->label('Phone')
                            ->disabled(),
                    ])->columns(3),

                Schemas\Components\Section::make('Application')
                    ->schema([
                        Schemas\Components\Select::make('job_id')
                            ->label('Job Position')
                            ->disabled()
                            ->relationship('job', 'title'),
                        Schemas\Components\FileUpload::make('cv_path')
                            ->label('CV/Resume')
                            ->disabled(),
                        Schemas\Components\Textarea::make('cover_letter')
                            ->label('Cover Letter')
                            ->rows(4)
                            ->disabled(),
                    ]),

                Schemas\Components\Section::make('Status Management')
                    ->schema([
                        Schemas\Components\Select::make('status')
                            ->label('Status')
                            ->options(JobApplication::STATUSES)
                            ->required(),
                        Schemas\Components\DateTimePicker::make('interview_date')
                            ->label('Interview Date'),
                        Schemas\Components\Textarea::make('interview_notes')
                            ->label('Interview Notes')
                            ->rows(2),
                        Schemas\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(2),
                        Schemas\Components\Textarea::make('admin_notes')
                            ->label('Internal Notes (HR Only)')
                            ->rows(3)
                            ->helperText('These notes are only visible to admins'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applied_at')
                    ->label('Applied')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job.title')
                    ->label('Position')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'received',
                        'warning' => 'shortlisted',
                        'info' => 'interview',
                        'danger' => 'rejected',
                        'success' => 'hired',
                    ])
                    ->formatStateUsing(fn($state) => JobApplication::STATUSES[$state] ?? $state),
                Tables\Columns\IconColumn::make('cv_path')
                    ->label('CV')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-circle'),
                Tables\Columns\TextColumn::make('interview_date')
                    ->label('Interview')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('applied_at', 'desc')
            ->filters([
                Filter::make('job_id')
                    ->form([
                        Schemas\Components\Select::make('job_id')
                            ->label('Job Position')
                            ->relationship('job', 'title')
                            ->placeholder('All Positions'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['job_id'],
                            fn(Builder $query): Builder => $query->where('job_id', $data['job_id']),
                        );
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->options(JobApplication::STATUSES),
                Filter::make('applied_at')
                    ->form([
                        Schemas\Components\DatePicker::make('from'),
                        Schemas\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn(Builder $query): Builder => $query->whereDate('applied_at', '>=', $data['from']))
                            ->when($data['until'], fn(Builder $query): Builder => $query->whereDate('applied_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('shortlist')
                        ->label('Shortlist')
                        ->icon('heroicon-o-check')
                        ->action(fn(JobApplication $record) => $record->markAsShortlisted())
                        ->visible(fn(JobApplication $record) => $record->status === 'received'),
                    Tables\Actions\Action::make('schedule_interview')
                        ->label('Schedule Interview')
                        ->icon('heroicon-o-calendar')
                        ->form([
                            Schemas\Components\DateTimePicker::make('interview_date')
                                ->label('Interview Date & Time')
                                ->required(),
                            Schemas\Components\Textarea::make('notes')
                                ->label('Notes'),
                        ])
                        ->action(function (JobApplication $record, array $data) {
                            $record->markAsInterview(
                                \Carbon\Carbon::parse($data['interview_date']),
                                $data['notes'] ?? null
                            );
                        })
                        ->visible(fn(JobApplication $record) => in_array($record->status, ['received', 'shortlisted'])),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->form([
                            Schemas\Components\Textarea::make('reason')
                                ->label('Rejection Reason'),
                        ])
                        ->action(function (JobApplication $record, array $data) {
                            $record->markAsRejected($data['reason'] ?? null);
                        })
                        ->visible(fn(JobApplication $record) => !in_array($record->status, ['rejected', 'hired'])),
                    Tables\Actions\Action::make('hire')
                        ->label('Mark as Hired')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn(JobApplication $record) => $record->markAsHired())
                        ->visible(fn(JobApplication $record) => $record->status === 'interview'),
                ]),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn($records) => redirect()->to(route('filament.admin.resources.job-applications.export', ['ids' => $records->pluck('id')->toArray()]))),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'view' => Pages\ViewJobApplication::route('/{record}'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
