<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class WorkOrderChangeOrderInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'workOrder.title' => TextEntry::make('workOrder.title'),
            'title' => TextEntry::make('title'),
            'estimated_amount' => TextEntry::make('estimated_amount')->money('EUR'),
            'status' => TextEntry::make('status'),
            'description' => TextEntry::make('description'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
