<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class CustomerSystemInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = CustomerSystemResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'system_type' => TextEntry::make('system_type'),
            'code' => TextEntry::make('code'),
            'customer_id' => TextEntry::make('customer_id'),
            'customer_site_id' => TextEntry::make('customer_site_id'),
            'description' => TextEntry::make('description'),
            'brand' => TextEntry::make('brand'),
            'model' => TextEntry::make('model'),
            'serial_number' => TextEntry::make('serial_number'),
            'power_kw' => TextEntry::make('power_kw'),
            'installation_date' => TextEntry::make('installation_date'),
            'is_active' => TextEntry::make('is_active'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
