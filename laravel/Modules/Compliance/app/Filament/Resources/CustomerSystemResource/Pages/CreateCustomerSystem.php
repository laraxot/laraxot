<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\CustomerSystemResource\Pages;

use Modules\Compliance\Filament\Resources\CustomerSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateCustomerSystem extends XotBaseCreateRecord
{
    protected static string $resource = CustomerSystemResource::class;
}
