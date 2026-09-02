<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class CategoriesTable extends XotBaseResourceTable
{
    public function getTableColumns(): array
    {
        return [
            'title' => TextColumn::make('title')->searchable()->sortable(),
            'slug' => TextColumn::make('slug'),
            'name' => TextColumn::make('name')->searchable(),
            'icon' => TextColumn::make('icon'),
            'in_leaderboard' => TextColumn::make('in_leaderboard')->badge(),
        ];
    }
}
