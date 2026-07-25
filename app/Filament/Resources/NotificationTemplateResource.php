<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Schemas;
use Filament\Schemas\Schema;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
class NotificationTemplateResource extends BaseResource
{
    protected static ?string $model = NotificationTemplate::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Schemas\Components\Section::make('Template Configuration')
                    ->schema([
                        Schemas\Components\TextInput::make('name')
                            ->label('Template Name')
                            ->required(),
                        Schemas\Components\Select::make('event')
                            ->label('Event')
                            ->options(NotificationTemplate::EVENTS)
                            ->required(),
                        Schemas\Components\CheckboxList::make('channels')
                            ->label('Channels')
                            ->options([
                                'email' => 'Email',
                                'sms' => 'SMS',
                                'whatsapp' => 'WhatsApp',
                            ])
                            ->default(['email']),
                    ])->columns(3),

                Schemas\Components\Section::make('Email Subject')
                    ->schema([
                        Schemas\Components\TextInput::make('subject')
                            ->label('Subject (English)'),
                        Schemas\Components\TextInput::make('subject_bn')
                            ->label('Subject (Bengali)'),
                        Schemas\Components\TextInput::make('subject_ar')
                            ->label('Subject (Arabic)'),
                    ]),

                Schemas\Components\Section::make('Message Body')
                    ->schema([
                        Schemas\Components\Textarea::make('body')
                            ->label('Body (English)')
                            ->rows(6)
                            ->placeholder('Use {{variable_name}} for dynamic content'),
                        Schemas\Components\Textarea::make('body_bn')
                            ->label('Body (Bengali)')
                            ->rows(6),
                        Schemas\Components\Textarea::make('body_ar')
                            ->label('Body (Arabic)')
                            ->rows(6),
                    ]),

                Schemas\Components\Section::make('Available Variables')
                    ->schema([
                        Schemas\Components\KeyValue::make('variables')
                            ->label('Variable Descriptions')
                            ->keyLabel('Variable Name')
                            ->valueLabel('Description'),
                    ])->collapsible(),

                Schemas\Components\Section::make('Status')
                    ->schema([
                        Schemas\Components\Toggle::make('is_active')
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
                        Schemas\Components\KeyValue::make('preview_variables')
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
