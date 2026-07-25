<?php

declare(strict_types=1);

namespace App\Filament\Resources\JobApplicationResource\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Schemas\Components\IconEntry;
use Filament\Schemas\Components\ImageEntry;
class ViewJobApplication extends ViewRecord
{
    protected static string $resource = JobApplicationResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Applicant Information')
                    ->schema([
                        TextEntry::make('full_name')->label('Full Name'),
                        TextEntry::make('email')->label('Email'),
                        TextEntry::make('phone')->label('Phone'),
                    ])->columns(3),

                Section::make('Application Details')
                    ->schema([
                        TextEntry::make('job.title')->label('Applied For'),
                        TextEntry::make('applied_at')->label('Applied At')->dateTime(),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match($state) {
                                'received' => 'gray',
                                'shortlisted' => 'warning',
                                'interview' => 'info',
                                'rejected' => 'danger',
                                'hired' => 'success',
                            }),
                    ])->columns(3),

                Section::make('Resume & Cover Letter')
                    ->schema([
                        IconEntry::make('cv_path')
                            ->label('CV/Resume')
                            ->boolean(),
                        TextEntry::make('cover_letter')
                            ->label('Cover Letter')
                            ->html(),
                    ]),

                Section::make('Interview & Review')
                    ->schema([
                        TextEntry::make('interview_date')->label('Interview Date')->dateTime(),
                        TextEntry::make('interview_notes')->label('Interview Notes')->html(),
                        TextEntry::make('reviewed_at')->label('Last Reviewed')->dateTime(),
                        TextEntry::make('reviewer.name')->label('Reviewed By'),
                    ])->columns(2),

                Section::make('Feedback')
                    ->schema([
                        TextEntry::make('rejection_reason')->label('Rejection Reason'),
                        TextEntry::make('admin_notes')
                            ->label('Internal Notes (HR Only)')
                            ->html(),
                    ]),
            ]);
    }
}
