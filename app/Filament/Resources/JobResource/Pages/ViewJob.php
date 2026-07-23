<?php

declare(strict_types=1);

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class ViewJob extends ViewRecord
{
    protected static ?string $resource = JobResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Job Information')
                    ->schema([
                        TextEntry::make('title')->label('Title'),
                        TextEntry::make('department')->label('Department'),
                        TextEntry::make('location')->label('Location'),
                        TextEntry::make('country')->label('Country'),
                    ])->columns(2),

                Section::make('Employment Details')
                    ->schema([
                        TextEntry::make('employment_type')
                            ->label('Type')
                            ->formatStateUsing(fn($state) => \App\Models\Job::EMPLOYMENT_TYPES[$state] ?? $state),
                        TextEntry::make('experience_level')
                            ->label('Experience')
                            ->formatStateUsing(fn($state) => \App\Models\Job::EXPERIENCE_LEVELS[$state] ?? $state),
                        TextEntry::make('deadline')->label('Deadline')->date(),
                    ])->columns(3),

                Section::make('Salary')
                    ->schema([
                        TextEntry::make('salary_min')->label('Min Salary')->money('SAR'),
                        TextEntry::make('salary_max')->label('Max Salary')->money('SAR'),
                        IconEntry::make('salary_visible')->label('Visible')->boolean(),
                    ])->columns(3),

                Section::make('Description')
                    ->schema([
                        TextEntry::make('description')->label('Job Description')->html(),
                    ]),

                Section::make('Requirements')
                    ->schema([
                        TextEntry::make('requirements')->label('Requirements')->html(),
                    ]),

                Section::make('Responsibilities')
                    ->schema([
                        TextEntry::make('responsibilities')->label('Responsibilities')->html(),
                    ]),

                Section::make('Benefits')
                    ->schema([
                        TextEntry::make('benefits')->label('Benefits')->html(),
                    ]),

                Section::make('Status')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match($state) {
                                'draft' => 'gray',
                                'published' => 'success',
                                'closed' => 'danger',
                            }),
                        IconEntry::make('is_featured')->label('Featured')->boolean(),
                    ])->columns(2),
            ]);
    }
}
