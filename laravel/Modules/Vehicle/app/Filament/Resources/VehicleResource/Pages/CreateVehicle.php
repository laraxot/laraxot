<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Vehicle\Filament\Resources\VehicleResource;

final class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;
}

