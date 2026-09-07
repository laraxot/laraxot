<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewCustomerSystem extends XotBaseViewRecord
{
    protected static string $resource = CustomerSystemResource::class;

    protected function getHeaderActions(): array
    {
        return ['edit' => EditAction::make()];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'system_type' => TextEntry::make('system_type'),
            'code' => TextEntry::make('code'),
            'customer_id' => TextEntry::make('customer_id'),
            'description' => TextEntry::make('description'),
            'media_count' => TextEntry::make('media_count'),
            'is_active' => TextEntry::make('is_active'),
        ];
    }
}
