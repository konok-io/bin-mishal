<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Template Name')
                            ->required(),
                        Forms\Components\Select::make('event')
                            ->label('Event')
                            ->options(NotificationTemplate::EVENTS)
                            ->required(),
                        Forms\Components\CheckboxList::make('channels')
                            ->label('Channels')
                            ->options([
                                'email' => 'Email',
                                'sms' => 'SMS',
                                'whatsapp' => 'WhatsApp',
                            ])
                            ->default(['email']),
                    ])->columns(3),

                Forms\Components\Section::make('Email Subject')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject (English)'),
                        Forms\Components\TextInput::make('subject_bn')
                            ->label('Subject (Bengali)'),
                        Forms\Components\TextInput::make('subject_ar')
                            ->label('Subject (Arabic)'),
                    ]),

                Forms\Components\Section::make('Message Body')
                    ->schema([
                        Forms\Components\Textarea::make('body')
                            ->label('Body (English)')
                            ->rows(6)
                            ->placeholder('Use {{variable_name}} for dynamic content'),
                        Forms\Components\Textarea::make('body_bn')
                            ->label('Body (Bengali)')
                            ->rows(6),
                        Forms\Components\Textarea::make('body_ar')
                            ->label('Body (Arabic)')
                            ->rows(6),
                    ]),

                Forms\Components\Section::make('Available Variables')
                    ->schema([
                        Forms\Components\KeyValue::make('variables')
                            ->label('Variable Descriptions')
                            ->keyLabel('Variable Name')
                            ->valueLabel('Description'),
                    ])->collapsible(),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Template')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('event')
                    ->label('Event'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->form([
                        Forms\Components\KeyValue::make('preview_variables')
                            ->label('Test Variables')
                            ->addButtonLabel('Add Variable'),
                    ])
                    ->action(function (array $data, NotificationTemplate $record) {
                        // Preview the template with test variables
                        $rendered = $record->render($data['preview_variables'] ?? []);
                        return redirect()->back()->with('notification_preview', $rendered);
                    }),
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
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
