<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceInfolist;

final class DeliveryNoteInfolist extends XotBaseResourceInfolist
{
    protected static string $resource = DeliveryNoteResource::class;
    /**
     * @return array<string, Component>
     */
    public function getInfolistSchema(): array
    {
        return [
            'id' => TextEntry::make('id'),
            'number' => TextEntry::make('number'),
            'supplier' => TextEntry::make('supplier.legal_name'),
            'received_on' => TextEntry::make('received_on')->date('d/m/Y'),
            'work_order_id' => TextEntry::make('work_order_id'),
            'notes' => TextEntry::make('notes'),
            'lines_count' => TextEntry::make('lines_count'),
            'created_at' => TextEntry::make('created_at')->dateTime(),
            'updated_at' => TextEntry::make('updated_at')->dateTime(),
        ];
    }
}
