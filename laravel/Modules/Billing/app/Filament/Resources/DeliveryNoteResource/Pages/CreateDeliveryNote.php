<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;

final class CreateDeliveryNote extends CreateRecord
{
    protected static string $resource = DeliveryNoteResource::class;
}
