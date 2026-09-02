<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ProfileResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

class ProfileInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'user' => TextEntry::make('user.name'),
            'email' => TextEntry::make('email'),
            'first_name' => TextEntry::make('first_name'),
            'last_name' => TextEntry::make('last_name'),
            'slug' => TextEntry::make('slug'),
            'extra' => TextEntry::make('extra')->html(),
        ];
    }
}
