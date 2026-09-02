<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Widgets;

use Filament\Tables\Table;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;

class LatestArticlesWidget extends XotBaseTableWidget
{
    public function table(Table $table): Table
    {
        return $table;
        /*
            ->query(
                // ...
            )
            ->columns([
                // ...
            ]);
        */
    }
}
