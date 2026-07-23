<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Compliance\Filament\Resources\CustomerSystemResource;

final class CreateCustomerSystem extends CreateRecord
{
    protected static string $resource = CustomerSystemResource::class;
}
