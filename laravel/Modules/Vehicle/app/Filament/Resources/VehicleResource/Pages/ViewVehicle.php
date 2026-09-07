<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewVehicle extends XotBaseViewRecord
{
    protected static string $resource = VehicleResource::class;
}
