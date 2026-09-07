<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewHeatingSystem extends XotBaseViewRecord
{
    protected static string $resource = HeatingSystemResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'code' => TextEntry::make('code'),
            'customer_id' => TextEntry::make('customer_id'),
            'description' => TextEntry::make('description'),
            'boilers_count' => TextEntry::make('boilers_count'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
