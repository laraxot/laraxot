<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\View;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Illuminate\Database\Eloquent\Model;
>>>>>>> bdc49995 (.)
=======
>>>>>>> a988596b (first)
use Modules\Lang\Filament\Resources\LangBaseResource;
use Modules\Notify\Filament\Forms\Components\HtmlLayoutPathSelect;
use Modules\Notify\Models\MailTemplate;
use Override;

class MailTemplateResource extends LangBaseResource
{
    protected static ?string $model = MailTemplate::class;

<<<<<<< HEAD
    /**
     * Restituisce lo schema del form per Filament.
     *
     * - Array associativo con chiavi stringhe
     * - Campi ricavati da migration/model: id, mailable, subject, html_template, text_template
     * - Le etichette, i placeholder e i testi di aiuto sono gestiti tramite LangServiceProvider
     * - File di traduzione: Modules/Notify/resources/lang/{locale}/mail_template.php
     */
    /**
     * @return array<string, Component>
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
        /** @var view-string $paramsBadgesView */
        $paramsBadgesView = 'notify::filament.components.params-badges';

        return [
            'mailable_slug_group' => Group::make()
                ->schema([
                    'mailable' => TextInput::make('mailable')
                        ->default('Modules\Notify\Emails\SpatieEmail')
                        ->required()
                        ->readonly()
                        ->maxLength(255),
                    'slug' => TextInput::make('slug')
                        ->required()
<<<<<<< HEAD
<<<<<<< HEAD
                        ->unique(ignoreRecord: true)])
=======
                        ->unique(ignoreRecord: true),
                ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                        ->unique(ignoreRecord: true)])
>>>>>>> a988596b (first)
                ->columns(2),
            /*
            'name_slug_group' => Group::make()
                ->schema([
                    TextInput::make('name')
                        ->label('Nome Template')
                        ->required()
                        ->afterStateUpdated(function (string $state, Set $set): void {
                            $set('slug', Str::slug($state));
                        }),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
<<<<<<< HEAD
<<<<<<< HEAD
                        ->unique(ignoreRecord: true)])
=======
                        ->unique(ignoreRecord: true),
                ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                        ->unique(ignoreRecord: true)])
>>>>>>> a988596b (first)
                ->columns(2),
            */

            'subject' => TextInput::make('subject')
                ->required()
                ->maxLength(255),
            'html_layout_path' => HtmlLayoutPathSelect::make('html_layout_path')
                ->required(),
            'html_template' => RichEditor::make('html_template')
                ->required()
                ->columnSpanFull(),
            'params_display' => View::make($paramsBadgesView)
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
                ->viewData(static fn (?Model $record): array => [
                    'params' => $record !== null && isset($record->params) ? $record->params : [],
                ])
                ->columnSpanFull()
                ->visible(static fn (?Model $record): bool => $record !== null && isset($record->params) && ! empty($record->params)),
=======
                ->viewData(static fn (mixed $record): array => [
                    'params' => is_object($record) && isset($record->params) ? $record->params : [],
                ])
                ->columnSpanFull()
                ->visible(static fn (mixed $record): bool => is_object($record) && isset($record->params) && ! empty($record->params)),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->viewData(static fn (?Model $record): array => [
                    'params' => $record !== null && isset($record->params) ? $record->params : [],
                ])
                ->columnSpanFull()
                ->visible(static fn (?Model $record): bool => $record !== null && isset($record->params) && ! empty($record->params)),
>>>>>>> bdc49995 (.)
=======
                ->viewData(static fn (mixed $record): array => [
                    'params' => is_object($record) && isset($record->params) ? $record->params : []])
                ->columnSpanFull()
                ->visible(static fn (mixed $record): bool => is_object($record) && isset($record->params) && ! empty($record->params)),
>>>>>>> a988596b (first)
            'text_template' => Textarea::make('text_template')
                ->maxLength(65535)
                ->columnSpanFull(),
            'sms_template' => Textarea::make('sms_template')
<<<<<<< HEAD
<<<<<<< HEAD
                ->columnSpanFull()];
=======
                ->columnSpanFull(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->columnSpanFull()];
>>>>>>> a988596b (first)
    }
=======
   
>>>>>>> 48f28c29 (.)
}
