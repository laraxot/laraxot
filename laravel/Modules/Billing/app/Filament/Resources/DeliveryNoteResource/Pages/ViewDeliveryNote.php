<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Filament\Actions\EditAction;
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
}
