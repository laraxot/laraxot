<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class HeatingSystemInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = HeatingSystemResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'code' => TextEntry::make('code'),
            'customer_id' => TextEntry::make('customer_id'),
            'customer_site_id' => TextEntry::make('customer_site_id'),
            'description' => TextEntry::make('description'),
            'fuel_type' => TextEntry::make('fuel_type'),
            'total_power_kw' => TextEntry::make('total_power_kw'),
            'plant_registry_code' => TextEntry::make('plant_registry_code'),
            'installation_date' => TextEntry::make('installation_date')->date(),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
