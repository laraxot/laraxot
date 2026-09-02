<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\BannerResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class BannersTable extends XotBaseResourceTable
{
    public function getTableColumns(): array
    {
        return [
            'title' => TextColumn::make('title')->searchable()->sortable(),
            'slug' => TextColumn::make('slug'),
            'link' => TextColumn::make('link')->limit(30),
            'position' => TextColumn::make('position')->sortable(),
            'active' => TextColumn::make('active')->badge(),
        ];
    }
}
