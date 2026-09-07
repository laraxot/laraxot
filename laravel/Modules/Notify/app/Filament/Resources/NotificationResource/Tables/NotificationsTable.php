<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class NotificationsTable extends XotBaseResourceTable
{
    public function getTableFilters(): array
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

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'type' => TextColumn::make('type')->sortable(),
            'status' => TextColumn::make('status')->sortable(),
            'read_at' => TextColumn::make('read_at')->dateTime()->sortable(),
            'sent_at' => TextColumn::make('sent_at')->dateTime()->sortable(),
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
