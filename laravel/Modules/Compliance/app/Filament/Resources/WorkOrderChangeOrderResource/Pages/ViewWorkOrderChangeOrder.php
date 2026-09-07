<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewWorkOrderChangeOrder extends XotBaseViewRecord
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'workOrder.title' => TextEntry::make('workOrder.title'),
            'title' => TextEntry::make('title'),
            'estimated_amount' => TextEntry::make('estimated_amount'),
            'status' => TextEntry::make('status'),
        ];
    }
}
