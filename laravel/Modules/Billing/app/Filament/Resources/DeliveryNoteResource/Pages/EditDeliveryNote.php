<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\Pages;

use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditDeliveryNote extends XotBaseEditRecord
{
    protected static string $resource = DeliveryNoteResource::class;
}
