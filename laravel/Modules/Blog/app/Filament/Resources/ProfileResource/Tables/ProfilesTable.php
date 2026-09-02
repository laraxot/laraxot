<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ProfileResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ProfilesTable extends XotBaseResourceTable
{
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable(),
            'user' => TextColumn::make('user.name')->searchable(),
            'email' => TextColumn::make('email')->searchable(),
            'first_name' => TextColumn::make('first_name')->searchable(),
            'last_name' => TextColumn::make('last_name')->searchable(),
            'slug' => TextColumn::make('slug'),
        ];
    }
}
