<?php

declare(strict_types=1);

namespace Modules\Vehicle\Filament\Resources\VehicleResource\Pages;

use Modules\Vehicle\Filament\Resources\VehicleResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditVehicle extends XotBaseEditRecord
{
    protected static string $resource = VehicleResource::class;
}
