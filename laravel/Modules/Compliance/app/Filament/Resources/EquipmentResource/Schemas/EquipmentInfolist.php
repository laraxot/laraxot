<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class EquipmentInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = EquipmentResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'name' => TextEntry::make('name'),
            'type' => TextEntry::make('type'),
            'serial_number' => TextEntry::make('serial_number'),
            'status' => TextEntry::make('status'),
            'last_checked_on' => TextEntry::make('last_checked_on')->date(),
            'next_check_due_on' => TextEntry::make('next_check_due_on')->date(),
            'maintenance_due_on' => TextEntry::make('maintenance_due_on')->date(),
            'assigned_work_order_id' => TextEntry::make('assignedWorkOrder.title'),
            'notes' => TextEntry::make('notes'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
