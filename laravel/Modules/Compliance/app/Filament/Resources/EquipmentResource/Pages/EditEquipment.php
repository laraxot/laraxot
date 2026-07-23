<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Compliance\Filament\Resources\EquipmentResource;

final class EditEquipment extends EditRecord
{
    protected static string $resource = EquipmentResource::class;
}
