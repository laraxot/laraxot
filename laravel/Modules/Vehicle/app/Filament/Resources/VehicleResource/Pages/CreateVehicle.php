<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateVehicle extends XotBaseCreateRecord
{
    protected static string $resource = VehicleResource::class;
}
