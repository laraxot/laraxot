<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class EmployeeInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'last_name' => TextEntry::make('last_name'),
            'first_name' => TextEntry::make('first_name'),
            'email' => TextEntry::make('email'),
            'is_active' => TextEntry::make('is_active'),
            'updated_at' => TextEntry::make('updated_at'),
        ];
    }
}
