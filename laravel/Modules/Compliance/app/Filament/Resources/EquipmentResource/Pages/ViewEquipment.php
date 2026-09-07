<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewEquipment extends XotBaseViewRecord
{
    protected static string $resource = EquipmentResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'name' => TextEntry::make('name'),
            'type' => TextEntry::make('type'),
            'serial_number' => TextEntry::make('serial_number'),
            'status' => TextEntry::make('status'),
            'next_check_due_on' => TextEntry::make('next_check_due_on'),
            'assignedWorkOrder.title' => TextEntry::make('assignedWorkOrder.title'),
        ];
    }
}
