<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotifyThemeResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Notify\Filament\Resources\NotifyThemeResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class NotifyThemesTable extends XotBaseResourceTable
{
    public function getTableFilters(): array
    {
        return [
            'lang' => SelectFilter::make('lang')->options(
                fn (): array => NotifyThemeResource::fieldOptions('lang'),
            ),
            'post_type' => SelectFilter::make('post_type')->options(
                fn (): array => NotifyThemeResource::fieldOptions('post_type'),
            ),
            'type' => SelectFilter::make('type')->options(
                fn (): array => NotifyThemeResource::fieldOptions('type'),
<<<<<<< HEAD
<<<<<<< HEAD
            )];
=======
            ),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            )];
>>>>>>> a988596b (first)
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'lang' => TextColumn::make('lang')->sortable(),
            'type' => TextColumn::make('type')->sortable(),
            'subject' => TextColumn::make('subject')->searchable(),
            'theme' => TextColumn::make('theme')->sortable(),
            'from_email' => TextColumn::make('from_email')->searchable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)];
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true)];
>>>>>>> a988596b (first)
    }
}
