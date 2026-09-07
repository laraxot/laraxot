<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Resources\Pages\PageRegistration;
use Modules\Notify\Filament\Resources\NotificationTemplateResource\Pages\PreviewNotificationTemplate;
use Modules\Notify\Models\NotificationTemplate;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class NotificationTemplateResource extends XotBaseResource
{
    protected static ?string $model = NotificationTemplate::class;

    /**
     * @return array<string, PageRegistration>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[Override]
<<<<<<< HEAD
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
            'name' => TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText(__('notify::template.form.name.helper'))
                ->columnSpan(['lg' => 2]),
            'subject' => TextInput::make('subject')
                ->required()
                ->maxLength(255)
                ->helperText(__('notify::template.form.subject.helper'))
                ->columnSpan(['lg' => 2])
                ->translateLabel(),
            'type' => Select::make('type')
                ->options(collect(NotificationTypeEnum::cases())
                    ->mapWithKeys(fn (NotificationTypeEnum $type): array => [$type->value => $type->getLabel()]))
                ->required()
                ->default(NotificationTypeEnum::EMAIL->value)
                ->helperText(__('notify::template.form.type.helper'))
                ->columnSpan(['lg' => 1]),
            'body_text' => Textarea::make('body_text')
                ->required()
                ->maxLength(65535)
                ->columnSpan(['lg' => 3])
                ->helperText(__('notify::template.form.body_text.helper'))
                ->rows(5)
                ->translateLabel(),
            'body_html' => Textarea::make('body_html')
                ->required()
                ->maxLength(65535)
                ->columnSpan(['lg' => 3])
                ->helperText(__('notify::template.form.body_html.helper'))
                ->rows(10)
                ->translateLabel(),
            'preview_data' => Textarea::make('preview_data')
                ->json()
                ->columnSpan(['lg' => 3])
                ->helperText(__('notify::template.form.preview_data.helper'))
                ->rows(5),
            'attachments' => SpatieMediaLibraryFileUpload::make('attachments')
                ->collection('attachments')
                ->multiple()
                ->maxFiles(5)
                ->maxSize(5120)
                ->acceptedFileTypes(['application/pdf', 'image/*'])
                ->columnSpan(['lg' => 3])
<<<<<<< HEAD
<<<<<<< HEAD
                ->helperText(__('notify::template.form.attachments.helper'))];
=======
                ->helperText(__('notify::template.form.attachments.helper')),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->helperText(__('notify::template.form.attachments.helper'))];
>>>>>>> a988596b (first)
    }

    #[Override]
=======
>>>>>>> dabf035f (fix(notify): fix static calls to instance-only getFormSchema/getInfolistSchema)
    public static function getPages(): array
    {
        return [
            ...parent::getPages(),
<<<<<<< HEAD
<<<<<<< HEAD
            'preview' => PreviewNotificationTemplate::route('/{record}/preview')];
=======
            'preview' => PreviewNotificationTemplate::route('/{record}/preview'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'preview' => PreviewNotificationTemplate::route('/{record}/preview')];
>>>>>>> a988596b (first)
    }

    /*
     * public static function getEloquentQuery(): Builder
     * {
     * return parent::getEloquentQuery()
     * ->orderBy('name');
     * }
     */
}
