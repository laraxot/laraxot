<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotifyThemeResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Notify\Filament\Resources\NotifyThemeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListNotifyThemes extends XotBaseListRecords
{
    protected static string $resource = NotifyThemeResource::class;

    /**
     * Colonne tabella NotifyTheme (condivise con ManageNotifyThemes).
     *
     * @return array<string, TextColumn>
     */
    public static function getNotifyThemeTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'lang' => TextColumn::make('lang')->sortable(),
            'type' => TextColumn::make('type')->sortable(),
            'post_id' => TextColumn::make('post_id')->sortable(),
            'post_type' => TextColumn::make('post_type')->sortable(),
            'logo_src' => TextColumn::make('logo_src')->sortable(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
<<<<<<< HEAD
<<<<<<< HEAD
                ->toggleable(isToggledHiddenByDefault: true)];
=======
                ->toggleable(isToggledHiddenByDefault: true),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->toggleable(isToggledHiddenByDefault: true)];
>>>>>>> a988596b (first)
    }

    #[Override]
    public function getTableColumns(): array
    {
        return self::getNotifyThemeTableColumns();
    }

    /**
     * @return array<string, SelectFilter>
     */
    public static function getNotifyThemeTableFilters(): array
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
=======
>>>>>>> a988596b (first)
            )];
    }

    #[Override]
    public function getTableFilters(): array
    {
        return self::getNotifyThemeTableFilters();
<<<<<<< HEAD
=======
            ),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }
}
