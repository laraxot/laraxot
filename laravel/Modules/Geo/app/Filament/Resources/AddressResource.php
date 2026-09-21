<?php

declare(strict_types=1);

namespace Modules\Geo\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Geo\Models\Address;

class AddressResource extends XotBaseResource
{
    protected static ?string $model = Address::class;
}
