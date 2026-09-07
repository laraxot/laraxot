<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditEquipment extends XotBaseEditRecord
{
    protected static string $resource = EquipmentResource::class;
}
