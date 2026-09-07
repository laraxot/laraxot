<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Resources\NotificationLogResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class NotificationLogsTable extends XotBaseResourceTable
{
    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'name' => TextColumn::make('name')->searchable(),
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()];
=======
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()];
>>>>>>> a988596b (first)
    }
}
