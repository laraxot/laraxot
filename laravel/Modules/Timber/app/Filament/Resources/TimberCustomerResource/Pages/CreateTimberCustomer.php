<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberCustomerResource\Pages;

use Modules\Timber\Filament\Resources\TimberCustomerResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateTimberCustomer extends XotBaseCreateRecord
{
    protected static string $resource = TimberCustomerResource::class;
}
