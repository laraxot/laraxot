<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class TimberAgentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = TimberAgentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'full_name' => TextEntry::make('full_name'),
            'email' => TextEntry::make('email'),
            'phone' => TextEntry::make('phone'),
            'zone' => TextEntry::make('zone'),
            'commission_percent' => TextEntry::make('commission_percent'),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
        ];
    }
}
