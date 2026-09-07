<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Modules\Notify\Models\Notification;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class NotificationResource extends XotBaseResource
{
    protected static ?string $model = Notification::class;

<<<<<<< HEAD
    /**
     * @return array<string, Field>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[Override]
    public static function getFormSchema(): array
=======

    // #[Override]
    public static function getFormSchemaOld(): array
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    #[Override]
    public static function getFormSchema(): array
>>>>>>> a988596b (first)
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
=======
    

>>>>>>> 48f28c29 (.)
}
