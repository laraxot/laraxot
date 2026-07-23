<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Compliance\Filament\Resources\EquipmentResource;

final class CreateEquipment extends CreateRecord
{
    protected static string $resource = EquipmentResource::class;
}
