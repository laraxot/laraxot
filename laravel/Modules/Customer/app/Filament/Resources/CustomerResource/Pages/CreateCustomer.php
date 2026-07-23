<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\Pages;

use Modules\Customer\Filament\Resources\CustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateCustomer extends XotBaseCreateRecord
{
    protected static string $resource = CustomerResource::class;
}
