<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Timber\Filament\Resources\TimberCustomerResource;

final class CreateTimberCustomer extends XotBaseCreateRecord
{
    protected static string $resource = TimberCustomerResource::class;
}
