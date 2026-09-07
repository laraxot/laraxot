<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Schemas;

use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class WorkOrderInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * @return array<string, Component|Entry>
     */
    public function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('code'),
                        TextEntry::make('status.name'),
                        TextEntry::make('customer.legal_name'),
                        TextEntry::make('site.name'),
                        TextEntry::make('primaryContact.full_name'),
                        TextEntry::make('title'),
                        TextEntry::make('scheduled_at')->dateTime(),
                        TextEntry::make('delivery_due_at')->dateTime(),
                        TextEntry::make('progress_percent'),
                        TextEntry::make('planned_minutes'),
                    ]),
                    TextEntry::make('description')->columnSpanFull(),
                    TextEntry::make('notes')->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
