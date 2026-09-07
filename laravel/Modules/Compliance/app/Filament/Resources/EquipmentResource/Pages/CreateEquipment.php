<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateEquipment extends XotBaseCreateRecord
{
    protected static string $resource = EquipmentResource::class;
}
