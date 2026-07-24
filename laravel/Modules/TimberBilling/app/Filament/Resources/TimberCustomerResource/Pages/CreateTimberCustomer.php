<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\TimberBilling\Filament\Resources\TimberCustomerResource;

final class CreateTimberCustomer extends CreateRecord
{
    protected static string $resource = TimberCustomerResource::class;
}
