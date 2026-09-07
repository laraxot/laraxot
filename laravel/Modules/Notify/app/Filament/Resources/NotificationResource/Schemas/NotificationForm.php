<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationResource\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component as SchemaComponent;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class NotificationForm extends XotBaseResourceForm
{
    /**
     * @return array<int|string, SchemaComponent>
     */
    public function getFormSchema(): array
    {
        return [
            'type' => TextInput::make('type')->required()->label('Notification Type'),
            'notifiable_type' => TextInput::make('notifiable_type')->required()->label('Notifiable Type'),
            'notifiable_id' => TextInput::make('notifiable_id')
                ->required()
                ->numeric()
                ->label('Notifiable ID'),
            'data' => Textarea::make('data')->label('Notification Data')->columnSpanFull(),
            'read_at' => DateTimePicker::make('read_at')->label('Read At')->nullable(),
            'created_by' => TextInput::make('created_by')->label('Created By')->disabled(),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_by' => TextInput::make('updated_by')->label('Updated By')->disabled()];
=======
            'updated_by' => TextInput::make('updated_by')->label('Updated By')->disabled(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_by' => TextInput::make('updated_by')->label('Updated By')->disabled()];
>>>>>>> a988596b (first)
    }
}
