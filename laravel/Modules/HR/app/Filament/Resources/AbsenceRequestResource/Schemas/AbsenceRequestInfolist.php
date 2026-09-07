<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class AbsenceRequestInfolist extends XotBaseResourceInfolist
{
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'type' => TextEntry::make('type'),
            'starts_at' => TextEntry::make('starts_at'),
            'ends_at' => TextEntry::make('ends_at'),
            'status' => TextEntry::make('status'),
        ];
    }
}
