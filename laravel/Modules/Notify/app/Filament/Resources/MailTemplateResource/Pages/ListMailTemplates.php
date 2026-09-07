<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\MailTemplateResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
use Modules\Notify\Filament\Resources\MailTemplateResource;
use Override;

class ListMailTemplates extends LangBaseListRecords
{
    protected static string $resource = MailTemplateResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public static function mailTemplateTableColumns(): array
    {
        return [
            'slug' => TextColumn::make('slug')->searchable()->sortable(),
            // TextColumn::make('mailable')->searchable()->sortable(),
            'subject' => TextColumn::make('subject')->searchable()->sortable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'counter' => TextColumn::make('counter')->searchable()->sortable()];
=======
            'counter' => TextColumn::make('counter')->searchable()->sortable(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'counter' => TextColumn::make('counter')->searchable()->sortable()];
>>>>>>> a988596b (first)
    }

    #[Override]
    public function getTableColumns(): array
    {
        return self::mailTemplateTableColumns();
    }
}
