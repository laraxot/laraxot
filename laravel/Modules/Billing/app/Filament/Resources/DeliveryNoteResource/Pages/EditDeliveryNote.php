<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;

final class EditDeliveryNote extends EditRecord
{
    protected static string $resource = DeliveryNoteResource::class;
}
