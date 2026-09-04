<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListDeliveryNotes extends XotBaseListRecords
{
    protected static string $resource = DeliveryNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
