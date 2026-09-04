<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateDeliveryNote extends XotBaseCreateRecord
{
    protected static string $resource = DeliveryNoteResource::class;
}
