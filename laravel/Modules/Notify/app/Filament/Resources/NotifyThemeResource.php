<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Modules\Notify\Models\NotifyTheme;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class NotifyThemeResource extends XotBaseResource
{
    protected static ?string $model = NotifyTheme::class;

    /**
<<<<<<< HEAD
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
            'lang' => Select::make('lang')->options(fn (): array => self::fieldOptions('lang')),
            'type' => Select::make('type')->options(fn (): array => self::fieldOptions('type')),
            'post_type' => Select::make('post_type')->options(fn (): array => self::fieldOptions('post_type')),
            'post_id' => TextInput::make('post_id'),
            'subject' => TextInput::make('subject'),
            'from' => TextInput::make('from'),
            'from_email' => TextInput::make('from_email'),
            'logo' => SpatieMediaLibraryFileUpload::make('logo_src')
                ->openable()
                ->downloadable()
                ->columnSpanFull()
                ->disk('uploads')
                ->directory('photos')
                ->preserveFilenames(),
            'logo_width' => TextInput::make('logo_width'),
            'logo_height' => TextInput::make('logo_height'),
            'theme' => Select::make('theme')
                ->options([
                    'empty' => 'empty',
                    'ark' => 'ark',
                    'minty' => 'minty',
                    'sunny' => 'sunny',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'widgets' => 'widgets'])
                ->default('empty'),
            'body' => Textarea::make('body')->columnSpanFull(),
            'body_html' => RichEditor::make('body_html')->columnSpanFull()];
<<<<<<< HEAD
=======
                    'widgets' => 'widgets',
                ])
                ->default('empty'),
            'body' => Textarea::make('body')->columnSpanFull(),
            'body_html' => RichEditor::make('body_html')->columnSpanFull(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }

    /**
=======
>>>>>>> 48f28c29 (.)
     * @return array<string, string>
     */
    public static function fieldOptions(string $field): array
    {
        return match ($field) {
            'lang' => [
                'it' => 'Italiano',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                'en' => 'English'],
            'type' => [
                'email' => 'Email',
                'sms' => 'SMS',
                'push' => 'Push Notification'],
            'post_type' => [
                'page' => 'Page',
                'post' => 'Post',
                'product' => 'Product'],
<<<<<<< HEAD
=======
                'en' => 'English',
            ],
            'type' => [
                'email' => 'Email',
                'sms' => 'SMS',
                'push' => 'Push Notification',
            ],
            'post_type' => [
                'page' => 'Page',
                'post' => 'Post',
                'product' => 'Product',
            ],
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            default => [],
        };
    }
}
