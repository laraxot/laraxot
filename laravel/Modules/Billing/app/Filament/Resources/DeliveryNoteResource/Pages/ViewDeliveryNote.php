<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewDeliveryNote extends XotBaseViewRecord
{
    protected static string $resource = DeliveryNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'number' => TextEntry::make('number'),
            'supplier.legal_name' => TextEntry::make('supplier.legal_name'),
            'received_on' => TextEntry::make('received_on'),
            'lines_count' => TextEntry::make('lines_count'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
