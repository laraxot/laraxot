<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationResource\Pages;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notify\Filament\Resources\NotificationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Override;

class ListNotifications extends XotBaseListRecords
{
    protected static string $resource = NotificationResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public static function notificationTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->numeric()->sortable(),
            'type' => TextColumn::make('type')->searchable()->sortable(),
            'notifiable' => TextColumn::make('notifiable.name')->searchable()->sortable(),
            'data' => TextColumn::make('data')->searchable(),
            'read_at' => TextColumn::make('read_at')->dateTime()->sortable(),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()];
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()];
>>>>>>> a988596b (first)
    }

    /**
     * @return array<string, Filter|SelectFilter>
     */
    public static function notificationTableFilters(): array
    {
        return [
            'read' => Filter::make('is_read')
                ->query(fn (Builder $query): Builder => $query->where('read_at', '!=', null))
                ->label('Read'),
            'unread' => Filter::make('is_unread')
                ->query(fn (Builder $query): Builder => $query->whereNull('read_at'))
                ->label('Unread'),
            'type' => SelectFilter::make('type')
                ->options([
                    'info' => 'Info',
                    'success' => 'Success',
                    'warning' => 'Warning',
<<<<<<< HEAD
<<<<<<< HEAD
                    'error' => 'Error'])
                ->multiple()];
=======
                    'error' => 'Error',
                ])
                ->multiple(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'error' => 'Error'])
                ->multiple()];
>>>>>>> a988596b (first)
    }

    #[Override]
    public function getTableColumns(): array
    {
        return self::notificationTableColumns();
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)

    #[Override]
    public function getTableFilters(): array
    {
        return self::notificationTableFilters();
    }
<<<<<<< HEAD
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
}
