<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Vehicle\Filament\Resources\VehicleResource;

final class EditVehicle extends EditRecord
{
    protected static string $resource = VehicleResource::class;
}

