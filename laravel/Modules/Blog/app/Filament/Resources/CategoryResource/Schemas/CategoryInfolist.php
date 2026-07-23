<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\CategoryResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class CategoryInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'title' => TextEntry::make('title'),
            'slug' => TextEntry::make('slug'),
            'name' => TextEntry::make('name'),
            'description' => TextEntry::make('description')->html(),
            'icon' => TextEntry::make('icon'),
            'in_leaderboard' => TextEntry::make('in_leaderboard')->badge(),
        ];
    }
}
