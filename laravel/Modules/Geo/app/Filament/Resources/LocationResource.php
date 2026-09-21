<?php

declare(strict_types=1);

namespace Modules\Geo\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Geo\Models\Location;

class LocationResource extends XotBaseResource
{
    protected static ?string $model = Location::class;
}
